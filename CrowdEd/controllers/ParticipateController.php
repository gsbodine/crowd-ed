<?php


/**
 * Description of ParticipateController
 * 
 * This controller contains most of the functionality of the crowd-editing 
 * functions of Crowd-Ed, including even the login stuff for the time being...
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
        $user = current_user();
        $item = $this->_helper->db->findById();
        
        if ($this->getRequest()->isPost()) {
            $this->_updatePersonElements();
            $item->setPostData($_POST);
            if ($item->save()) {
               $this->updateEditStatus($item, 'QA');
               $this->_savePersonNames($item);
               $item->addTags($_POST['hidden-tags']);
               $successMessage = $this->_getEditSuccessMessage($item);
                if ($successMessage != '') {
                    $this->_helper->flashMessenger($successMessage, 'success');
                }
                $this->_redirectAfterEdit($item);
            } else {
                $this->_helper->flashMessenger($item->getErrors());
            } 
        }
        $tags = $this->_helper->db->getTable('Tag')->findAll();
        $this->view->elementSets = $this->_getItemElementSets($item);
        $this->view->assign(compact('item','tags'));
        
        parent::editAction();
    }
    
    
    
    public function forgotPasswordAction(){
        
    }
    
    public function profileAction() {
        $user = current_user();
        $entity = new Entity();
        $entity->getEntityByUserId($user->id);
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
                
        if (!$requireTermsOfService || $_POST['terms'] == 1) {
            
            unset($_POST['role']);
            
            $entity->setPostData($_POST);
            
            $user->setPostData($_POST);
            $user->name = $entity->getName();
            $user->role = CROWDED_USER_ROLE; 

            if ($user->save()) {
                $newUser = $this->_helper->db->getTable('User')->findByEmail($user->email);
                $entity->user_id = $newUser->id;
                $entity->save();
                if ($this->sendActivationEmail($user)) {
                    $this->_helper->flashMessenger(__('Thanks for creating a user account.  To complete your registration, please check your email and click the provided link to activate your account.'),'success');
                } else {
                    $this->_helper->flashMessenger(__('There was an issue trying to create your account. Please contact the site administrator'),'error');
                }
                $this->_helper->redirector('index', 'participate', '', array());
            } else {
               $this->_helper->flashMessenger($user->getErrors());
            }
        } else {
            $this->_helper->flashMessenger(__('You cannot register unless you understand and agree to the Terms Of Service and Privacy Policy.'),'warning');
        }		
        
        $this->view->assign(compact('emailSent', 'requireTermsOfService', 'user', 'entity'));
       
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
    
    public function updateEditStatus($item,$statusName='QA') {
        $editStatus = new EditStatus;
        $status_id = $editStatus->getStatusIdByName($statusName);
        $editStatusItem = new EditStatusItems();
        $editStatusItem->edit_status_id = 1;
        $editStatusItem->item_id = $item->id;
        $editStatusItem->save();
    }
    
    protected function _getItemElementSets($item) {
        return $this->_helper->db->getTable('ElementSet')->findByRecordType('Item');
    }
    
    protected function _getEditSuccessMessage($record) {
         $successMessage = __('Your changes have been saved.');
         return $successMessage;     
    }
    
    protected function _redirectAfterEdit($record) {
        $this->_helper->redirector('show', 'items', '', array('id'=>$record->id));
    }
    
   
   /* PRIVATE FUNCTIONS */
    
    private function _updatePersonElements() {
          
        if (!$_POST['PersonNames']) {
            return;
        }
        $i = 0;
        $ppn = $_POST['PersonNames'];
        foreach ($ppn as $key => $pnValues) {
           if (is_array($pnValues)) {
                 foreach ($pnValues as $pn => $values) {
                    $elementId = $pn['element_id'];
                    $catName = $pn['title'].' '.$pn['firstname'].' '.$pn['middlename'].' '.$pn['lastname'].' '.$pn['suffix'];
                    $post['Elements'][$elementId][$i]['text'] = $catName;
                    $i++;
                }
            } else {
                $elementId = $pnValues['element_id'];
                $catName = $pnValues['title'].' '.$pnValues['firstname'].' '.$pnValues['middlename'].' '.$pnValues['lastname'].' '.$pnValues['suffix'];
                $_POST['Elements'][$elementId][0]['text'] = $catName;
           }
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
        //return $post;
    }
   
    private function _getPersonNames($item) {
        return $this->getTable('PersonName')->findByRecordId($item->id);
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

