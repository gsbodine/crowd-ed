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
            'size' => '30',
            'required' => true,
            'validators' => array(
                //todo: finish validation;
            )
        ));
        
        $this->addElement('text','last_name',array(
            'label' => __('Last Name'),
            //'description' => __('Your last name (surname)'),
            'size' => '30',
            'required' => true,
            'validators' => array(
                //todo: finish validation;
            )
        ));
        
        $this->addElement('text','institution',array(
            'label' => __('Institution or Affiliation'),
            //'description' => __('Your institution or group affiliation (if applicable)'),
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
        
        $this->addElement('checkbox','terms',array('label'=>'You must agree to the following Terms and Conditions of this site.',
                                                    'description'=>get_option('crowded_terms_of_service')));
        
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
