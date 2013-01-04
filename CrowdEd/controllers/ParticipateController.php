<?php


/**
 * Description of ParticipateController
 * 
 * Almost all of the user-related stuff here is from the MyOmeka plug-in 
 * codebase (at least for the time being) to make it functional.
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

//require_once APP_DIR . '/controllers/ItemsController.php';

class CrowdEd_ParticipateController extends Omeka_Controller_AbstractActionController {
    
    public function init() {
       $this->_helper->db->setDefaultModelName('Item');
    }
    
    public function indexAction() {
        
    }   
    
    public function randomAction() {
        
    }
    
    public function editAction() {
        $record = $this->_helper->db->findById();
        $user = current_user();
        
        if ($this->getRequest()->isPost()) {
            $this->_updatePersonElements();
            $record->setPostData($_POST);
            if ($record->save(false)) {
               $this->_savePersonNames($item);
               $record->addTags($_POST['tags']);
               $successMessage = $this->_getEditSuccessMessage($record);
                if ($successMessage != '') {
                    $this->_helper->flashMessenger($successMessage, 'success');
                }
                $this->_redirectAfterEdit($record);
            } else {
                $this->_helper->flashMessenger($record->getErrors());
            } 
        }
        // $this->view->elementSets = $this->_getItemElementSets($item);
        $this->view->assign(compact('item'));
        parent::editAction();
    }
    
    private function _updatePersonElements() {
        
        $post = $_POST;    
        if (!$post['PersonNames']) {
            return;
        }
        
        $ppn = $post['PersonNames'];
        foreach ($ppn as $key => $pnValues) {
           /* if (is_array($pnValues)) {
                 foreach ($pnValues as $pn => $values) {
                    $elementId = $pn['element_id'];
                    $catName = $pn['title'].' '.$pn['firstname'].' '.$pn['middlename'].' '.$pn['lastname'].' '.$pn['suffix'];
                    $post['Elements'][$elementId][$i]['text'] = $catName;
                }
            } else {*/
                $elementId = $pnValues['element_id'];
                $catName = $pnValues['title'].' '.$pnValues['firstname'].' '.$pnValues['middlename'].' '.$pnValues['lastname'].' '.$pnValues['suffix'];
                $_POST['Elements'][$elementId][0]['text'] = $catName;
           // }
            //var_dump($_POST['Elements']);
        }
       
        return $_POST; 
    }
    
    private function _savePersonNames($item) {
        $post = $_POST;    
        if (!$post['PersonNames']) {
            return;
        }
        foreach ($post['PersonNames'] as $key => $ppn) {
            $personName = new PersonName;
            
            if (substr($key, 0, 3) == 'new') {
                foreach($post['PersonNames'][$key] as $pn) {
                    $personName->setArray(
                         array(
                             'firstname'=>$pn['firstname'],
                             'lastname'=>$pn['lastname'],
                             'middlename'=>$pn['middlename'],
                             'title'=>$pn['title'],
                             'suffix'=>$pn['suffix'],
                             'element_id'=>$pn['element_id'],
                             'record_id'=>$pn['record_id']
                         )
                    );
                    $personName->save();
                }
            } elseif (is_int($key)) {
                $personName->setArray(
                     array(
                         'id'=>$key,
                         'firstname'=>$ppn['firstname'],
                         'lastname'=>$ppn['lastname'],
                         'middlename'=>$ppn['middlename'],
                         'title'=>$ppn['title'],
                         'suffix'=>$ppn['suffix'],
                         'element_id'=>$ppn['element_id'],
                         'record_id'=>$ppn['record_id']
                     )
                );
                $personName->save();
            } else {
                echo 'postPersonName[$id] did not work :( ';
                die();
            }
        }
    }
    
    public function forgotAction(){
        
    }
    
    public function profileAction() {
        $user = current_user();
        $this->view->assign(compact('user'));
    }
    
    public function loginAction() {
        $current = current_user();
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
    
   public function _getPersonNames($item) {
        return $this->getTable('PersonName')->findByRecordId($item->id);
    } 
   
    protected function _getItemElementSets($item) {
        return $this->_helper->db->getTable('ElementSet')->findByRecordType('Item');
        //return $this->getTable('ElementSet')->findForItems($item);
    }

    
    protected function _getEditSuccessMessage($record) {
         $successMessage = __('Your changes have been saved.');
         return $successMessage;     
    }
    
    protected function _redirectAfterEdit($record) {
        $this->_helper->redirector('show', 'items', '', array('id'=>$record->id));
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

