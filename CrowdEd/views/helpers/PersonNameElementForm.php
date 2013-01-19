<?php

/**
 * Generate (and override) the form markup for entering element text metadata in Omeka.
 *
 * @package CrowdEd
 */

//require_once APP_DIR . '/views/helpers/ElementForm.php';

class CrowdEd_View_Helper_PersonNameElementForm extends Omeka_View_Helper_ElementForm {
    
    protected $_element;
    protected $_record;

    private function _personNameElement($element, $record, $options = array()) {
        $extraFieldCount = isset($options['extraFieldCount']) ? $options['extraFieldCount'] : null;

        $columnSpan = isset($options['columnSpan']) ? $options['columnSpan'] : '6';
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '3';
        $this->_element = $element;
        $record->loadElementsAndTexts(); 
        $this->_record = $record;
        
        $html = '';
        $html .= '<div class="field span'. $columnSpan .'" id="element-' . html_escape($element->id) . '">';
        $html .= $this->_displayFieldLabel();
        //$html .= $this->_displayValidationErrors();
        $html .= $this->_displayPersonNameFields($this->_record,$this->_element);  
        $html .= '<input type="submit" class="add-element btn btn-small btn-info" value="Add another author" id="add_element_' . $element->id . '" name="add_element_' . $element->id . '">';
        $html .= '</div>';
        return $html;
    }
    
    public function personNameElementForm(Element $element, Omeka_Record_AbstractRecord $record, $options = array()) {
        $html = '';
        if (is_array($element)) {
                foreach ($element as $key => $e) {
                    $html .= $this->_personNameElement($e, $record, $options);
                }
            } else {
                $html = $this->_personNameElement($element, $record, $options);
            }
	return $html;
    }
    /*public function elementForm(Element $element, Omeka_Record $record, $options = array()) {
        $extraFieldCount = isset($options['extraFieldCount']) ? $options['extraFieldCount'] : null;

        $columnSpan = isset($options['columnSpan']) ? $options['columnSpan'] : '6';
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '3';
        $this->_element = $element;
        $record->loadElementsAndTexts(); 
        $this->_record = $record;
        
        $personMixin = new PersonName;
        $isPersonName = $personMixin->isPersonNameElement($this->_element);
        
        $html = '';
        $html .= '<div class="field span'. $columnSpan .'" id="element-' . html_escape($element->id) . '">';
        $html .= $this->_displayFieldLabel();
        $html .= $this->_displayValidationErrors();
        if ($isPersonName){
            $html .= $this->_displayPersonNameFields($this->_record,$this->_element);
        } else {
            $html .= $this->_displayFormFields($options=array('fieldColumnSpan'=>$fieldColumnSpan),$extraFieldCount); 
        }
        $html .= '</div>';
        return $html;
    }*/
    
    
    protected function _displayPersonNameFields($record,$element,$newIndex=0) {
        $html = '';
        $pn = new PersonName;
        $personNames = $pn->getPersonNamesByRecordAndElementIds($record->id,$element->id);
        
        if (is_Array($personNames) && count($personNames) >= 1) { 
            foreach ($personNames as $personName) {
                if ($personName['element_id'] == $element->id) {
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][title]', $personName->title,$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][firstname]', $personName->firstname,$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][middlename]', $personName->middlename,$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][lastname]', $personName->lastname,$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][suffix]', $personName->suffix,$options=array('class'=>'input-small','placeholder'=>'Suffix (e.g. Jr.)'));
                    $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][record_id]" value="'. $record->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][id]" value="'. $personName->id .'" />';

                   // $html .= $this->_displayFormControls();
                } else {
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][title]','',$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][firstname]','',$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][middlename]','',$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][lastname]','',$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][suffix]','',$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)'));
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][record_id]" value="'. $record->id .'" />'; 
                   // $html .= $this->_displayFormControls();
                }
            }
        } else if ($personNames instanceof PersonName && $personNames['element_id'] == $element->id) {
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personNames->id .'][title]', $personNames->title,$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personNames->id .'][firstname]', $personNames->firstname,$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personNames->id .'][middlename]', $personNames->middlename,$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personNames->id .'][lastname]', $personNames->lastname,$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personNames->id .'][suffix]', $personNames->suffix,$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)'));
            $html .= '<input type="hidden" name="PersonNames['. $personNames->id .'][element_id]" value="'. $element->id .'" />';
            $html .= '<input type="hidden" name="PersonNames['. $personNames->id .'][record_id]" value="'. $record->id .'" />';
            $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][id]" value="'. $personName->id .'" />';

           // $html .= $this->_displayFormControls();
        } else {
            $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][title]','',$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
            $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][firstname]','',$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
            $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][middlename]','',$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
            $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][lastname]','',$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
            $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][suffix]','',$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)'));
            $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][element_id]" value="'. $element->id .'" />';
            $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][record_id]" value="'. $record->id .'" />'; 
           // $html .= $this->_displayFormControls();
        }
 
        return $html;
    }
    
    protected function _displayPersonNameFormInput($inputNameStem, $value, $options=array()) {
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '3';    
        //$fieldDataType = $this->_getElementDataType();
        if ($this->_element['name'] == 'Creator') {
            $elementName = 'Author';
        } else {
            $elementName = $this->_element['name'];
        }
        $html = '';
        $html .= $this->view->formText($inputNameStem, $value, $options);
        return $html;
    }
    
    protected function _displayFieldLabel() {
        $html = '<div class="form-inline"><label>';
        switch ($this->_getFieldLabel()) {
            case 'Creator':
                $html .= '<i class="icon-user"></i> ';
                break;
            case 'Recipient':
                $html .= '<i class="icon-envelope"></i> ';
                break;
        }
        if ($this->_getFieldLabel() == 'Creator') {
            $label = 'Author';
        } else {
            $label = $this->_getFieldLabel();
        }
        $html .=  __($label) . '</label>';
        //$html .= $this->_displayExplanation();
        $html .= '</div>';
        return $html;
    }
    
    protected function _displayExplanation() {
        $html = ' <a href="#" rel="tooltip" class="tooltipper" title="';
        $html .= $this->_getFieldDescription() .'"><i class="icon-info-sign"></i></a>';

        return $html;
    }
    
    
}