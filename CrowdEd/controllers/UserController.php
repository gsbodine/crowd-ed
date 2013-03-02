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
        
        $openRegistration = (get_option('guest_user_open') == 1);
        $instantAccess = (get_option('guest_user_instant_access') == 1);
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
                        return;
                    }
                    if($openRegistration) {
                        $message = "Thank you for registering. Please check your email for a confirmation message. Once you have confirmed your request, you will be able to log in.";
                        $this->_helper->flashMessenger($message, 'success');
                        $activation = UsersActivations::factory($user);
                        $activation->save();

                    } else {
                        $message = "Thank you for registering. Please check your email for a confirmation message. Once you have confirmed your request and an administrator activates your account, you will be able to log in.";
                        $this->_helper->flashMessenger($message, 'success');
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
                                'class'         => 'textinput',
                        )
        );        
        
        $oldPassword = $form->getElement('current_password');
        $oldPassword->setOrder(0);
        $form->addElement($oldPassword);
        
        //$form->removeElement('new_password_confirm');
        $form->setSubmitButtonText('Update');
        $form->setDefaults($user->toArray());
        $this->view->form = $form;
        
        if (!$this->getRequest()->isPost() || !$form->isValid($_POST)) {
            return;
        }  
        
        if($user->password != $user->hashPassword($_POST['current_password'])) {
            $this->_helper->flashMessenger(__("Incorrect password"), 'error');
            return;
        }
        
        $user->setPassword($_POST['new_password']);
        $user->setPostData($_POST);
        try {
            $user->save($_POST);
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
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

