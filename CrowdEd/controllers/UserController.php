<?php


/**
 * Description of Crowd-Ed UserController
 * 
 * This controller contains most of the functionality for Crowd-Ed-specific, user-related account stuff
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

require_once CROWDED_DIR . '/forms/User.php';
require_once GUEST_USER_PLUGIN_DIR . '/controllers/UserController.php';

class CrowdEd_UserController extends GuestUser_UserController {
    
    public function init() {
        parent::init();
        $this->_helper->db->setDefaultModelName('User');
    }
   
    public function registerAction() {
        
        $user = new User();
        $entity = new Entity();
        
        $openRegistration = (get_option('guest_user_open') == 'on');
        $instantAccess = (get_option('guest_user_instant_access') == 'on');
        $requireTermsOfService = get_option('crowded_require_terms_of_service');

        $form = $this->_getForm(array('user'=>$user,'entity'=>$entity));
        $form->setSubmitButtonText(__('Create Account'));
        $this->view->form = $form;
        
        if (!$this->getRequest()->isPost() || !$form->isValid($_POST)) {
            return;
        }		        
                
        if (!$requireTermsOfService || $_POST['terms'] == 1) {
            
            $user->role = CROWDED_USER_ROLE;
            if($openRegistration || $instantAccess) {
                $user->active = true;
            }
            $user->setPassword($_POST['new_password']);
            $user->setPostData($_POST);
            $entity->setPostData($_POST);
            $user->name = $entity->getName();
            
            try {
                if ($user->save()) {
                    $token = $this->_createToken($user);
                    $this->_sendConfirmationEmail($user, $token); 
                    
                    // entity stuff for crowd-ed -- the main reason for this function override
                    $newUser = $this->_helper->db->getTable('User')->findByEmail($user->email);
                    $entity->user_id = $newUser->id;
                    $entity->save();
                    
                    if($instantAccess) {
                        $authAdapter = new Omeka_Auth_Adapter_UserTable($this->_helper->db->getDb());
                        $authAdapter->setIdentity($user->username)->setCredential($_POST['new_password']);                    
                        $authResult = $this->_auth->authenticate($authAdapter);
                        if (!$authResult->isValid()) {
                            if ($log = $this->_getLog()) {
                                $ip = $this->getRequest()->getClientIp();
                                $log->info("Failed login attempt from '$ip'.");
                            }
                            $this->_helper->flashMessenger($this->getLoginErrorMessages($authResult), 'error');
                            return;
                        }             
                        $activation = UsersActivations::factory($user);
                        $activation->save();
                        $this->_helper->flashMessenger(__("You are logged in temporarily. Please check your email for a confirmation message. Once you have confirmed your request, you can log in without time limits."));
                        $session = new Zend_Session_Namespace;
                        if ($session->redirect) {
                            $this->_helper->redirector->gotoUrl($session->redirect);
                        }
                        $this->_helper->redirector('index', 'participate');
                    }
                    if($openRegistration) {
                        $message = "Thank you for registering. Please check your email for a confirmation message. Once you have confirmed your request, you will be able to log in.";
                        $this->_helper->flashMessenger($message, 'success');
                        $activation = UsersActivations::factory($user);
                        $activation->save();
                        $user->active = 1;
                        $user->save();
                        $this->_helper->redirector('index', 'participate');

                    } else {
                        $message = "Thank you for registering. Please check your email for a confirmation message. Once you have confirmed your request, you will be able to log in.";
                        $this->_helper->flashMessenger($message, 'success');
                        $this->_helper->redirector('index', 'participate');
                    }
                }
            } catch (Omeka_Validator_Exception $e) {
                $this->flashValidationErrors($e);
            }
                
        } else {
            $this->_helper->flashMessenger(__('You cannot register unless you understand and agree to the Terms Of Service and Privacy Policy.'),'warning');
        }		
        
        $this->view->assign(compact('emailSent', 'requireTermsOfService', 'user', 'entity'));
       
   }
   
   public function updateAccountAction() {
        $user = current_user();
        $e = new Entity;
        $entity = $e->getEntityFromUser($user); 
        
        $form = $this->_getForm(array('user'=>$user,'entity'=>$entity));
        $form->getElement('new_password')->setLabel(__("New Password"));
        $form->getElement('new_password')->setRequired(false);
        $form->getElement('new_password_confirm')->setRequired(false);
        $form->addElement('password', 'current_password',
                        array(
                                'label'         => __('Current Password'),
                                'required'      => true,
                                'class'         => 'textinput user-form-input'
        ));        
        
        $oldPassword = $form->getElement('current_password');
        //$oldPassword->setOrder(0);
        $form->addElement($oldPassword);
        
        $form->addDisplayGroup(
                array('current_password'),
                'current-password-group',
                array('class'=>'user-fieldset',
                    'legend'=>'Current Password',
                    'description'=>'For security reasons, your current password is required to make changes to your account.')
                    );
        
        $form->getDisplayGroup('current-password-group')->setOrder(-1);
        
        $form->getDisplayGroup('names-group')->setDescription('Update your display names at any time to be included in citations.');
        $form->getDisplayGroup('password-group')->setDescription('If you don\'t want to change your password at this time, simply leave the following fields blank.');
        
        $form->setDisplayGroupDecorators(array('Description','FormElements','Fieldset'));
        
        $form->setSubmitButtonText('Update');
        $form->setDefaults($user->toArray());
        $form->setDefaults($entity->toArray());
        $this->view->form = $form;
        
        if (!$this->getRequest()->isPost() || !$form->isValid($_POST)) {
            return;
        }  
        if (!$requireTermsOfService || $_POST['terms'] == 1) {
            if($user->password != $user->hashPassword($_POST['current_password'])) {
                $this->_helper->flashMessenger(__("Incorrect password"), 'error');
                return;
            }

            if (trim($_POST['new_password']) != '') {
                $user->setPassword($_POST['new_password']);
            } else {
                $_POST['new_password'] = null;
            }
            $user->setPostData($_POST);
            $entity->setPostData($_POST);
            try {
                $user->save($_POST);
                $entity->save($_POST);
            } catch (Omeka_Validator_Exception $e) {
                $this->flashValidationErrors($e);
            }     
        } else {
            $this->_helper->flashMessenger(__('You cannot register unless you understand and agree to the Terms Of Service and Privacy Policy.'),'warning');
        }
    }
    
    public function confirmAction() {
        
        $db = get_db();
        $token = $this->getRequest()->getParam('token');
        $records = $db->getTable('GuestUserToken')->findBy(array('token'=>$token));
        $record = $records[0];
        if($record) {
            $record->confirmed = true;
            $record->save();
            $user = $db->getTable('User')->find($record->user_id);
            $activation = UsersActivations::factory($user);
            $activation->save();
            $user->active = 1;
            $user->save();
            $this->_sendAdminNewConfirmedUserEmail($user);
            $this->_sendConfirmedEmail($user);
            $message = "Please check the email we just sent you for the next steps! You're almost there!";
            $this->_helper->flashMessenger($message, 'success');
            $this->redirect('users/login');
        } else {
            $this->_helper->flashMessenger('Invalid token', 'error');
        }
    }
    
    protected function _sendConfirmationEmail($user, $token) {
        $transport = $this->_getSMTP();
        $siteTitle = get_option('site_title');
        $url = WEB_ROOT . '/user/confirm/token/' . $token->token;
        $siteUrl = absolute_url('/');
        $subject = "Your request to join $siteTitle";
        $body = "You have registered for an account on <a href='$siteUrl'>$siteTitle</a>. Please confirm your registration by following <a href='$url'>this link</a>.  If you did not request to join $siteTitle please disregard this email.";
        $mail = $this->_getMail($user, $body, $subject);
        try {
            $mail->send($transport);
        } catch (Exception $e) {
            _log($e);
            _log($body);
        }
    }
    
    protected function _sendConfirmedEmail($user) {
        $transport = $this->_getSMTP();
        $siteTitle = get_option('site_title');
        $body = "Thanks for joining $siteTitle!";
        if(get_option('guest_user_open') == 'on') {
            $body .= "\n\n You can now log in using the password you chose.";
        } else {
            $body .= "\n\n When an administrator approves your account, you will receive another message that you" .
                    "can log in with the password you chose.";
        }
        $subject = "Registration for $siteTitle";
        $mail = $this->_getMail($user, $body, $subject);
        try {
            $mail->send($transport);
        } catch (Exception $e) {
            _log($e);
            _log($body);
        }

    }
    
    protected function _sendAdminNewConfirmedUserEmail($user) {
        $transport = $this->_getSMTP();
        $siteTitle = get_option('site_title');
        $url = WEB_ROOT . "/admin/users/edit/" . $user->id;
        $subject = "New request to join $siteTitle";
        $body = "A new user has confirmed that they want to join $siteTitle.  ";
        $body .= "\n\n<a href='$url'>" . $user->username . "</a>";
        $mail = $this->_getMail($user, $body, $subject);
        $mail->clearRecipients();
        $mail->addTo(get_option('administrator_email'), "$siteTitle Administrator");
         try {
            $mail->send($transport);
        } catch (Exception $e) {
            _log($body);
        }
    }
   
   
   /* PRIVATE FUNCTIONS */
        
   private function _getSMTP() {
       $config = array(
        'ssl' => 'tls',
        'port' => 587,
        'auth' => 'login',
        'username' => 'garrick.bodine@gmail.com',
        'password' => '');
 
        $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
        return $transport;
   }
    
    private function _getUserEntityForm(User $user, Entity $entity) {
        $form = new CrowdEd_Form_UserEntity(array(
            'user' => $user,
            'entity' => $entity)
        );
        
        fire_plugin_hook('crowded_user_form', array('form' => $form, 'user' => $user, 'entity' => $entity));
        
        return $form;
     }
     
   protected function _getForm($options) {
        
        $form = new CrowdEd_Form_User($options);
        $form->removeElement('submit');
        $form->addElement('submit', 'submit', array('label' => 'Register','class'=>'btn btn-primary','style'=>'margin-top:1em;'));
        return $form;        
    }
    
   
    
}

