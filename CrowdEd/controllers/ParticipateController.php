<?php


/**
 * Description of ParticipateController
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

require_once 'User.php';
require_once 'Item.php';

class CrowdEd_ParticipateController extends Omeka_Controller_Action {
    public function init() {
        
    }
    
    public function indexAction() {
        
    }   
    
    public function itemAction() {
        require_once CROWDED_DIR . '/forms/Item.php';
        
        $itemId = $this->_getParam('id');
        $item = $this->findById($itemId, 'Item');
        $this->view->assign(compact('item'));
        
        $itemForm = new CrowdEd_Form_Item;
        
        $this->view->form = $itemForm;
        
        if (!$this->getRequest()->isPost()) {
            return;
        }
    }
    
     public function editAction() {
        // From ItemsController in Omeka
        $this->view->elementSets = $this->_getItemElementSets();
        
        if ($user = $this->getCurrentUser()) {
            $item = $this->findById();
            if ($this->isAllowed('edit', $item)) {
                return parent::editAction();    
            }
        }
        $this->forbiddenAction();
    }
    
    public function profileAction() {
        // TODO: complete/show user profile page
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
       // TODO: this will be the 'create profile' action
   }
    
    public function createUserAction() {
        // uses registerAction() from MyOmeka plugin code for now -- at least until I get to go over it...
        $emailSent = false; //true only if an registration email has been sent 
		
		$user = new User();
		$user->role = CROWDED_USER_ROLE; // TODO: create/verify user roles for Crowd-Ed
		
                $requireTermsOfService = get_option('crowded_require_terms_of_service');

		try {
		    if ($this->getRequest()->isPost()) {		        
		        if (!$requireTermsOfService || terms_of_service_checked_form_input()) {
		            // Do not allow anyone to manipulate the role on this form.
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
}

