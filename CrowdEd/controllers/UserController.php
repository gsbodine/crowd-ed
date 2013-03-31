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
        
        $openRegistration = true;
        $instantAccess = (get_option('guest_user_instant_access') == 'on');
        $requireTermsOfService = get_option('crowded_require_terms_of_service');

        $form = $this->_getForm(array('user'=>$user,'entity'=>$entity));
        
        if(Omeka_Captcha::isConfigured() && (get_option('guest_user_recaptcha') == 1)) {
            $form->addElement('captcha', 'captcha',  array(
                'class' => 'hidden',
                'style' => 'display: none;',
                'label' => "Help us cut down on spam: please verify you're a human.",
                'type' => 'hidden',
                'captcha' => Omeka_Captcha::getCaptcha()
            ));
            
            $form->getElement('captcha')->setOrder(55);
        }
        
        $form->setSubmitButtonText(__('Create Account'));
        $form->getElement('submit')->setOrder(100);
        $this->view->form = $form;
        
        if (current_user()) {
            $this->_helper->redirector('profile', 'participate');
        }
        
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
                    
                    $message = "Thank you for registering. Please check your email for a confirmation message. Once you have confirmed your request, you will be able to log in.";
                    $this->_helper->flashMessenger($message, 'success');
                    $activation = UsersActivations::factory($user);
                    $activation->save();
                    $this->_helper->redirector('index', 'participate');
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
        $requireTermsOfService = get_option('crowded_require_terms_of_service');
        
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
        $form->addElement($oldPassword);
        
        $form->getElement('current_password')->setOrder(5);
        
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
        
        $form->setSubmitButtonText('Update Account');
        $form->getElement('submit')->setOrder(100);
        
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
            $user->name = $entity->getName();
            
            try {
                if ($user->save()) {
                    $entity->save();
                }
            } catch (Omeka_Validator_Exception $e) {
                $this->flashValidationErrors($e);
            }   
        } else {
            $this->_helper->flashMessenger(__('You cannot register unless you understand and agree to the Terms Of Service and Privacy Policy.'),'warning');
            return;
        }
        
        $this->_helper->redirector('profile', 'participate');
    }
    
  
    protected function _sendConfirmedEmail($user) {
        $transport = $this->_getSMTP();
        $siteTitle = get_option('site_title');
        
        $body = "<p><strong>Thanks for joining $siteTitle (MBDA)!</strong></p>";
        $body .= "<p>Your account is now active, and we hope you'll <a href=\"http://mbda.berry.edu/get-started\">get started</a> and begin researching and editing the collection soon.";
        
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

