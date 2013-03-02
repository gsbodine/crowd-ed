<?php

class CrowdEd_Form_User extends Omeka_Form_User {
    
    private $_entity;
    
    public function init() {
        parent::init();
        
        
        $this->removeElement('name');
        $this->removeElement('submit');
        
        // adapt inputs length, bootstrap-style
        $this->getElement('email')->setAttrib('class','span4');
        $this->getElement('username')->setAttrib('class','span2');
        $this->getElement('username')->getDecorator('description')->clearOptions();
        
        $this->addElement('text','first_name',array(
            'label' => __('First Name'),
            //'description' => __('Your first name'),
            'value'=> $this->_entity->first_name,
            'size' => '30',
            'class' => 'span3',
            'required' => true,
            'validators' => array(
                // TODO: finish validation;
            )
        ));
        
        $this->addElement('text','last_name',array(
            'label' => __('Last Name'),
            //'description' => __('Your last name (surname)'),
            'value' => $this->_entity->last_name,
            'size' => '30',
            'class'=> 'span3',
            'required' => true,
            'validators' => array(
                // TODO: finish validation;
            )
        ));
        
        /*$this->addDisplayGroup(
                array('first_name','last_name'),'dg',
                array('legend'=>'Display Name','description'=>'Although not required, your first and last name will be used for attibution for any contributions you make to the site.'));
        */
        $this->addElement('text','institution',array(
            'label' => __('Institution or Affiliation'),
            //'description' => __('Your institution or group affiliation (if applicable)'),
            'value' => $this->_entity->institution,
            'class' => 'span4',
            'required' => false,
            'validators' => array(
                // TODO: finish validation;
            )
        ));
        
        $this->addElement('password', 'new_password',
            array(
                    'label'         => __('Password'),
                    'required'      => true,
                    'class'         => 'textinput',
                    'validators'    => array(
                        array('validator' => 'NotEmpty', 'breakChainOnFailure' => true, 'options' => 
                            array(
                                'messages' => array(
                                    'isEmpty' => __("New password must be entered.")
                                )
                            )
                        ),
                        array(
                            'validator' => 'Confirmation', 
                            'options'   => array(
                                'field'     => 'new_password_confirm',
                                'messages'  => array(
                                    Omeka_Validate_Confirmation::NOT_MATCH => __('New password must be typed correctly twice.')
                                )
                             )
                        ),
                        array(
                            'validator' => 'StringLength',
                            'options'   => array(
                                'min' => User::PASSWORD_MIN_LENGTH,
                                'messages' => array(
                                    Zend_Validate_StringLength::TOO_SHORT => __("New password must be at least %min% characters long.")
                                )
                            )
                        )
                    )
            )
        );
        $this->addElement('password', 'new_password_confirm',
                        array(
                                'label'         => 'Password again for match',
                                'required'      => true,
                                'class'         => 'textinput',
                                'errorMessages' => array(__('New password must be typed correctly twice.'))
                        )
        );
        
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
            
            // TODO: fix route; use request
            if (current_url() != '/participate/edit-profile') {
                $check = $this->createElement('checkbox',
                                  'terms', array(
                                    'label'=>'I agree to the Terms and Conditions of this site.',
                                    'class'=>'checkbox',
                                  ));
                $check->addDecorator('Label', array('class' => 'checkbox inline','placement'=>'APPEND'));
                $check->addDecorator('HtmlTag',array('tag'=>'span'));
                $this->addElement($check);
            }
        }
        
        if(Omeka_Captcha::isConfigured() && (get_option('guest_user_recaptcha') == 'on')) {
            $this->addElement('captcha', 'captcha',  array(
                'class' => 'hidden',
                'style' => 'display: none;',
                'label' => "Please verify you're a human",
                'type' => 'hidden',
                'captcha' => Omeka_Captcha::getCaptcha()
            ));
        }
        
        
        $this->addElement('submit', 'submit', array('label' => 'Submit','class'=>'btn btn-primary','style'=>'margin-top:1em;'));
    }
    
    public function setEntity(Entity $entity) {
        $this->_entity = $entity;
    }
    
    public function getDefaultElementDecorators() {
        return array(
            array('Description', array('tag' => 'div', 'class' => 'tooltip helpText', 'escape'=>false)), 
            'ViewHelper', 
            array('Errors', array('class' => 'error')),
            array(array('InputsTag' => 'HtmlTag'), array('tag' => 'div', 'class' => 'inputs')), 
            array('Label', array('tag' => 'div')),
            array(array('FieldTag' => 'HtmlTag'), array('tag' => 'div', 'class' => 'field'))
        );
    }
        
        
}
