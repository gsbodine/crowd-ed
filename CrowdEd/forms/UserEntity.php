<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of UserEntity
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */


class CrowdEd_Form_UserEntity extends Omeka_Form {
    
    private $_submitButtonText;
    
    private $_user;
    
    private $_entity;
    
    public function init() {
        parent::init();
        
        $this->addElement('text','username',array(
            'label' => __('Username'),
            //'description' => __('Username must contain only letters and/or numbers and have 30 or fewer characters.'),
            'value'=>$this->_user->username,
            'required' => true,
            'size' => '30',
            'validators' => array(
                array('validator' => 'NotEmpty', 'breakChainOnFailure' => true, 'options' => 
                    array(
                        'messages' => array(
                            Zend_Validate_NotEmpty::IS_EMPTY => __('Username is required.')
                        )
                    )
                ),
                array('validator' => 'Alnum', 'breakChainOnFailure' => true, 'options' =>
                    array(
                        'messages' => array(
                            Zend_Validate_Alnum::NOT_ALNUM =>
                                __('Username must contain only letters and numbers.')
                        )
                    )
                ),
                array('validator' => 'StringLength', 'breakChainOnFailure' => true, 'options' => 
                    array(
                        'min' => User::USERNAME_MIN_LENGTH,
                        'max' => User::USERNAME_MAX_LENGTH,
                        'messages' => array(
                            Zend_Validate_StringLength::TOO_SHORT =>
                                __('Username must be at least %min% characters long.'),
                            Zend_Validate_StringLength::TOO_LONG =>
                                __('Username must be at most %max% characters long.')
                        )
                    )
                ),
                array('validator' => 'Db_NoRecordExists', 'options' => 
                    array(
                        'table'     =>  $this->_user->getTable()->getTableName(), 
                        'field'     =>  'username',
                        'exclude'   =>  array(
                            'field' => 'id',
                            'value' => (int)$this->_user->id
                        ),
                        'adapter'   =>  $this->_user->getDb()->getAdapter(), 
                        'messages'  =>  array(
                            'recordFound' => __('This username is already in use.')
                        )
                    )
                )
            )
        ));
        
        $this->addElement('text','first_name',array(
            'label' => __('First Name'),
            //'description' => __('Your first name'),
            'value'=> $this->_entity->first_name,
            'size' => '30',
            'required' => true,
            'validators' => array(
                //todo: finish validation;
            )
        ));
        
        $this->addElement('text','last_name',array(
            'label' => __('Last Name'),
            //'description' => __('Your last name (surname)'),
            'value' => $this->_entity->last_name,
            'size' => '30',
            'required' => true,
            'validators' => array(
                //todo: finish validation;
            )
        ));
        
        $this->addElement('text','institution',array(
            'label' => __('Institution or Affiliation'),
            //'description' => __('Your institution or group affiliation (if applicable)'),
            'value' => $this->_entity->institution,
            'size' => '30',
            'required' => false,
            'validators' => array(
                //todo: finish validation;
            )
        ));
        
        $invalidEmailMessage = __('Your email address appears to be invalid.');
        $this->addElement('text', 'email', array(
            'label' => __('Email'),
            'size' => '30',
            'required' => true,
            'value' => $this->_user->email,
            'validators' => array(
                array('validator' => 'NotEmpty', 'breakChainOnFailure' => true, 'options' => array(
                    'messages' => array(
                        Zend_Validate_NotEmpty::IS_EMPTY => __('Email is required.')
                    )
                )),
                array('validator' => 'EmailAddress', 'options' => array(
                    'messages' => array(
                        Zend_Validate_EmailAddress::INVALID  => $invalidEmailMessage,
                        Zend_Validate_EmailAddress::INVALID_FORMAT => $invalidEmailMessage,
                        Zend_Validate_EmailAddress::INVALID_HOSTNAME => $invalidEmailMessage
                    )
                )),
                array('validator' => 'Db_NoRecordExists', 'options' => array(
                    'table'     =>  $this->_user->getTable()->getTableName(), 
                    'field'     =>  'email',
                    'exclude'   =>  array(
                        'field' => 'id',
                        'value' => (int)$this->_user->id
                    ),
                    'adapter'   =>  $this->_user->getDb()->getAdapter(), 
                    'messages'  =>  array(
                        'recordFound' => __('This email address is already in use.')
                    )
                ))
            )
        ));
        
        if (get_option('crowded_terms_of_service')) {
            
            $serviceTerms = html_entity_decode(get_option('crowded_terms_of_service'));
            $termsModal = '<div id="termsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">';
            $termsModal .='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="termsModalLabel">Terms and Conditions</h3></div>';
            $termsModal .='<div class="modal-body">' . $serviceTerms . '</div>';
            $termsModal .='<div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button></div></div>';
            
            $terms = new CrowdEd_Form_Element_Modal (
                'formNote',
                array('value' => $termsModal,
                        'description'=>'<a href="#termsModal" role="button" class="text-warning" data-toggle="modal"><i class="icon-legal"></i> '. get_option('site_title').' Terms and Conditions</a>')
            );
            
            $terms->removeDecorator('HtmlTag');
            $terms->removeDecorator('Label');
            $terms->setOptions(array('escape'=>false));
            $terms->getDecorator('Description')->setOption('escape',false);
                          
            $this->addElement($terms);
        
            if (current_url() != '/participate/edit-profile') {
                $check = $this->createElement('checkbox',
                                  'terms',
                                  array(
                                    'label'=>'I agree to the Terms and Conditions of this site.',
                                    'class'=>'checkbox',
                                    
                                  ));
                $check->addDecorator('Label', array('class' => 'checkbox inline','placement'=>'APPEND'));
                $check->addDecorator('HtmlTag',array('tag'=>'span'));
                $this->addElement($check);
            }
        }
        
        $this->addElement('submit', 'submit', array(
            'label' => $this->_submitButtonText,
            'class' => 'btn btn-primary'
        ));
        
        
    }
    
    public function setSubmitButtonText($text) {
        if (!$this->getElement('submit')) {
            $this->_submitButtonText = $text;
        } else {
            $this->submit->setLabel($text);
        }
    }
       
    public function setUser(User $user) {
         $this->_user = $user;
    }
    
    public function setEntity(Entity $entity) {
        $this->_entity = $entity;
    }
    
}

?>
