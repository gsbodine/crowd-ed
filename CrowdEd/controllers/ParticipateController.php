<?php


/**
 * Description of ParticipateController
 * 
 * Almost all of the user-related stuff here is from the MyOmeka plug-in 
 * codebase (at least for the time being) to make it functional.
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

require_once CROWDED_DIR . '/forms/UserEntity.php';

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
        $entity = new Entity();
        $entity->getEntityByUser($user);
        $this->view->assign(compact('user','entity'));
    }
    
    public function loginAction() {
        $current = current_user();
        $this->view->assign(compact($current));

    }
    
    public function logoutAction() {
        // TODO: Create logout method
    }
    
   public function joinAction() {
        
        $user = new User();
        $entity = new Entity();
        $requireTermsOfService = get_option('crowded_require_terms_of_service');

        $form = $this->_getUserEntityForm($user,$entity);
        $form->setSubmitButtonText(__('Create Account'));
        $this->view->form = $form;
        
        if (!$this->getRequest()->isPost() || !$form->isValid($_POST)) {
            return;
        }		        
                
        if (!$requireTermsOfService || terms_of_service_checked_form_input()) {
            
            unset($_POST['role']);
            
            $entity->setPostData($_POST);
            
            $user->setPostData($_POST);
            $user->name = $entity->getName();
            $user->role = CROWDED_USER_ROLE; // TODO: create/verify user roles for Crowd-Ed

            if ($user->save()) {
                $newUser = $this->_helper->db->getTable('User')->findByEmail($user->email);
                $entity->user_id = $newUser->id;
                $entity->save();
                if ($this->sendActivationEmail($user)) {
                    $this->_helper->flashMessenger(__('Thank for registering for a user account.  To complete your registration, please check your email and click the provided link to activate your account.'),'success');
                } else {
                    $this->_helper->flashMessenger(__('There was an issue trying to register your account. Please contact the site administrator'),'error');
                }
                $this->_helper->redirector();
            } else {
               $this->_helper->flashMessenger($user->getErrors());
            }
        } else {
            $this->_helper->flashMessenger(__('You cannot register unless you understand and agree to the Terms Of Service and Privacy Policy.'),'warning');
        }		
        
        $this->view->assign(compact('emailSent', 'requireTermsOfService', 'user', 'entity'));
       
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
        
        // send the user an email telling them about their new user account
        $siteTitle  = get_option('site_title');
        $from       = get_option('administrator_email');
        $body       = __('Welcome!')
                    ."\n\n"
                    . __('Your account for the %s repository has been created. Please click the following link to activate your account:',$siteTitle)."\n\n"
                    . WEB_ROOT . "/admin/users/activate?u={$ua->url}\n\n"
                    . __('%s Administrator', $siteTitle);
        $subject    = __('Activate your account with the %s repository', $siteTitle);
        
        $mail = new Zend_Mail();
        $mail->setBodyText($body);
        $mail->setFrom($from, "$siteTitle Administrator");
        $mail->addTo($user->email, $user->name);
        $mail->setSubject($subject);
        $mail->addHeader('X-Mailer', 'PHP/' . phpversion());
        try {
            $mail->send();
            return true;
        } catch (Zend_Mail_Transport_Exception $e) {
            $logger = $this->getInvokeArg('bootstrap')->getResource('Logger');
            if ($logger) {
                $logger->log($e, Zend_Log::ERR);
            }
            return false;
        }
    }
        
     private function _getUserEntityForm(User $user, Entity $entity) {
        $form = new CrowdEd_Form_UserEntity(array(
            'user' => $user,
            'entity' => $entity)
        );
        
        fire_plugin_hook('crowded_user_form', array('form' => $form, 'user' => $user, 'entity' => $entity));
        
        return $form;
     }
}

