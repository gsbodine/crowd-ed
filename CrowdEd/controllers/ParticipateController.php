<?php


/**
 * Description of ParticipateController
 * 
 * Almost all of the user-related stuff here is from the MyOmeka plug-in 
 * codebase (at least for the time being) to make it functional.
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

require_once 'User.php';
require_once 'Item.php';
require_once APP_DIR . '/controllers/UsersController.php';

class CrowdEd_ParticipateController extends UsersController {
    
    public function init() {
       $this->_modelClass = 'Item';
    }
    
    public function indexAction() {
        
    }   
    
    public function editAction() {
        $itemId = $this->_getParam('id');
        $item = $this->findById($itemId, 'Item');
        $user = Omeka_Context::getInstance()->getCurrentUser();
        $this->view->addHelperPath(CROWDED_DIR . '/helper','CrowdEd_View_Helper');
        
        if (!$this->getRequest()->isPost()) {
            $elementSets = $this->_getItemElementSets($item);
            $this->view->assign(compact('item'));
            $this->view->assign($elementSets);
            
        } else {
            try {
                if ($item->saveForm($_POST)) {
                   $item->addTags($_POST["tags"],$user);
                   $successMessage = $this->_getEditSuccessMessage($item);
                   if ($successMessage != '') {
                        $this->flashSuccess($successMessage);
                    }
                    $this->redirect->gotoSimple('show','items', '', array('id'=>$itemId));
                }
            } catch (Omeka_Validator_Exception $e) {
                $this->flashValidationErrors($e);
            } 
            $this->view->assign(compact('item'));
        }
    }
    
    public function forgotAction(){
        
    }
    
    public function profileAction() {
        $user = Omeka_Context::getInstance()->getCurrentUser();
        $this->view->assign(compact('user'));
    }
    
    public function loginAction() {
        // TODO: Finish login method
        $current = Omeka_Context::getInstance()->getCurrentUser();
        $this->view->assign(compact($current));

    }
    
    public function logoutAction() {
        // TODO: Create logout method
    }
    
   public function joinAction() {
       // uses registerAction() from MyOmeka plugin code
        $emailSent = false;
		
        $user = new User();
        $user->role = CROWDED_USER_ROLE; // TODO: create/verify user roles for Crowd-Ed

        $requireTermsOfService = get_option('crowded_require_terms_of_service');

        try {
            if ($this->getRequest()->isPost()) {		        
                if (!$requireTermsOfService || terms_of_service_checked_form_input()) {
                    unset($_POST['role']);
                        $user->saveForm($_POST);
                                $this->sendActivationEmail($user);
                                $this->flashSuccess('Thank for registering for a user account.  To complete your registration, please check your email and click the provided link to activate your account.');
                                $emailSent = true;
                } else {
                        $this->flash('You cannot register unless you understand and agree to the Terms Of Service and Privacy Policy.');
                }
            }			
        } catch (Omeka_Validator_Exception $e) {
                $this->flashValidationErrors($e);
        }
        
        $this->view->assign(compact('emailSent', 'requireTermsOfService', 'user'));
       
   }
    
    protected function _getItemElementSets($item) {
        return $this->getTable('ElementSet')->findForItems($item);
    }

    
    protected function _getEditSuccessMessage() {
         $successMessage = __('Your changes to the item have been saved.');
         return $successMessage;     
    }
    
    public function sendActivationEmail($user) {
        $ua = new UsersActivations;
        $ua->user_id = $user->id;
        $ua->save();
		
        $toEmail = $user->Entity->email;
        $toName = $user->Entity->first_name . ' ' . $user->Entity->last_name;

        $this->view->user = $user;
        $this->view->activationSlug = $ua->url;
        $this->view->siteTitle = get_option('site_title');

        $mail = new Zend_Mail();
        $mail->setBodyText($this->view->render('participate/join-email.php'));
        $mail->setFrom(get_option('administrator_email'), $this->view->siteTitle . ' Administrator');
        $mail->addTo($toEmail, $toName);
        $mail->setSubject("Activate your account with the {$this->view->siteTitle}");
        $mail->send();
	}
}

