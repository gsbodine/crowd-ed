<?php

class CrowdEd_Form_User extends Omeka_Form_User {
    
    private $_entity;
    
    public function init() {
        parent::init();
        
        $this->removeElement('name');
        $this->removeElement('submit');
        
        $this->getElement('email')->setAttrib('class','span4 user-form-input');
        
        $this->getElement('username')->setAttrib('class','span2 user-form-input');
        $this->getElement('username')->setDescription($this->_getHelpText($this->getElement('username')->getDescription()));
        
        
        
        $this->addElement('text','first_name',array(
            'label' => __('First Name'),
            'description' => $this->_getHelpText('Your first (given) name'),
            'value'=> $this->_entity->first_name,
            'class' => 'span3 user-form-input',
            'required' => false,
            'validators' => array(
                // TODO: finish validation;
            )
        ));
        
        $this->addElement('text','last_name',array(
            'label' => __('Last Name'),
            'description' => $this->_getHelpText('Your last name (surname or family name)'),
            'value' => $this->_entity->last_name,
            'class'=> 'span3 user-form-input',
            'required' => false,
            'validators' => array(
                // TODO: finish validation;
            )
        ));
        
        
        $this->addElement('text','institution',array(
            'label' => __('Institution or Affiliation'),
            'description' => $this->_getHelpText('Your institutional or organizational affiliation (if applicable)'),
            'value' => $this->_entity->institution,
            'class' => 'span4 user-form-input',
            'required' => false,
            'validators' => array(
                // TODO: finish validation;
            )
        ));
        
        
        $this->addElement('password', 'new_password',
            array(
                    'label'         => __('New Password'),
                    'required'      => true,
                    'class'         => 'textinput user-form-input',
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
                        'class'         => 'textinput user-form-input',
                        'errorMessages' => array(__('New password must be typed correctly twice.'))
                )
        );
        
        $privacy = $this->createElement('checkbox',
                                  'private', array(
                                  'label'=>'Make my profile private.',
                                  'value'=>$this->_entity->private,
                                  'class'=>'checkbox'
                                  ));
        $privacy->addDecorator('Label', array('class' => 'checkbox inline','placement'=>'APPEND'));
        $privacy->addDecorator('HtmlTag',array('tag'=>'span', 'class'=>'privacy-checkbox'));
        
        $this->addElement($privacy);
        
       
        $this->addDisplayGroup(
            array('first_name','last_name','institution'),
            'names-group',
            array('legend'=>'Names for Attribution',
                'class'=>'user-fieldset',
                'description'=>'Although not required, your first and last name will be used for attribution (e.g. in item citations) for any contributions you make to the site. Your institution (if desired) will be shown in your public profile.')
        );
        
        $this->addDisplayGroup(
            array('username','email'),
            'username-group',
            array('legend'=>'Username and Email',
                'class'=>'user-fieldset',
                'description'=>'Although your username and email may be changed later, they must both be unique within the site.')
        );
        
        $this->addDisplayGroup(
            array('new_password','new_password_confirm'),
            'password-group',
            array('class'=>'user-fieldset',
                'legend'=>'Password')
        );
        
        $this->addDisplayGroup(
            array('private'),
            'private-group',
            array('legend'=>'Profile Privacy Settings',
                'class'=>'user-fieldset',
                'description'=>'Check the box below if you do NOT wish your name to be included in citations, community progress listings, or display a public profile. Don\'t worry, we never publicly display your email address.')
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
                $this->addDisplayGroup(
                    array('formNote','terms'),
                    'terms-group',
                    array('legend'=>'Terms and Conditions',
                        'class'=>'user-fieldset'
                        ));
        
                $this->getDisplayGroup('terms-group')->setDecorators(array('FormElements','Fieldset'));
        
            }
            
            $this->setDisplayGroupDecorators(array('Description','FormElements','Fieldset'));
        
        
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
            array('Description', array('tag' => 'span', 'class' => 'popHelp', 'escape'=>false, 'placement'=>'append')), 
            'ViewHelper', 
            array('Errors', array('class' => 'error')),
            array('Label', array('class'=>'edit-label','requiredPrefix'=>'* ')),
            array(array('FieldTag' => 'HtmlTag'), array('tag' => 'div', 'class' => 'field'))
        );
    }
    
    public function getDefaultDisplayGroupDecorators() {
        return array(
            array('Description', array('tag' => 'div', 'class' => 'text-warning', 'escape'=>false, 'placement'=>'prepend')), 
        );
    }
    
    private function _getHelpText($text) {
        $helpText = '';
        if ($text) {
            $helpText = ' <a class="helpText" href="#" rel="tooltip" title="' . html_escape($text) .'" data-placement="right"><i class="icon-question-sign"></i></a>';
        }
        return $helpText;
    }
      
        
}
