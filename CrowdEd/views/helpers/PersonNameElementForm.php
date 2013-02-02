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

    public function personNameElementForm(Element $element, Omeka_Record_AbstractRecord $record, $options = array()) {
        $columnSpan = isset($options['columnSpan']) ? $options['columnSpan'] : 6;
        $extraFieldCount = isset($options['extraFieldCount']) ? $options['extraFieldCount'] : null;
        
        $this->_element = $element;
        $record->loadElementsAndTexts();
        $this->_record = $record;
        
        $html = '';
        $html .= '<div class="field span'. $columnSpan .'" id="element-' . html_escape($element->id) . '">';
        $html .= $this->_displayFieldLabel();
        //$html .= $this->_displayValidationErrors();
        //$html = $this->_personNameElement($element, $record, $options);
        
        $html .= $this->_displayPersonNameFields($this->_record,$this->_element,$extraFieldCount);  
        $html .= '<div><input type="submit" class="add-element btn btn-small btn-info" value="Add another author" id="add_element_' . $element->id . '" name="add_element_' . $element->id . '"></div>';
        $html .= '</div>';
        return $html;
    }
    
    private function _personNameElement($element, $record, $options = array()) {
        
        $columnSpan = isset($options['columnSpan']) ? $options['columnSpan'] : '6';
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '3';
        
        $this->_element = $element;
        $record->loadElementsAndTexts(); 
        $this->_record = $record;
        
        
    }
    
    
    
    private function _getPersonsCount($recordId,$elementId) {
        if ($this->_isPosted()) {
            $personCount = count($_POST['PersonNames']);
        } else {
            $pn = new PersonName();
            $personCount = count($pn->getPersonNamesByRecordAndElementIds($recordId,$elementId));
        }
        
        return $personCount ? $personCount : 1;
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
    
    
    protected function _displayPersonNameFields($record,$element,$extraFieldCount=0) {
        $html = '';
        if ($this->_isPosted()) {
            $personNames = $_POST['PersonNames'];
        } else {
            $pn = new PersonName;
            $personNames = $pn->getPersonNamesByRecordAndElementIds($record->id,$element->id);
        }
        if ($extraFieldCount > 0) {
            for ($i = 0; $i < $extraFieldCount; $i++) {
                $personNames[] = new PersonName();
            }
        }
       // $fieldCount = count($personNames) + (int) $extraFieldCount;
        
        
        //for ($i=0; $i < $fieldCount; $i++) {
        //if (is_array($personNames) && count($personNames) >= 1) { 
        $newIndex = 1;
        foreach ($personNames as $personName) {
               if ($personName['element_id'] == $element->id) {
                    // Case 1
                    $html .= '<div class="input-block">';
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][title]', $personName->title,$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][firstname]', $personName->firstname,$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][middlename]', $personName->middlename,$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][lastname]', $personName->lastname,$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][suffix]', $personName->suffix,$options=array('class'=>'input-small','placeholder'=>'Suffix (e.g. Jr.)'));
                    $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][record_id]" value="'. $record->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][id]" value="'. $personName->id .'" />';
                    $html .= '</div>';
                   // $html .= $this->_displayFormControls();
               } else {
                    // Case 2
                    $html .= '<div class="input-block">';
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][title]','',$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][firstname]','',$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][middlename]','',$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][lastname]','',$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][suffix]','',$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)'));
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][record_id]" value="'. $record->id .'" />'; 
                    $html .= '</div>';
                    $newIndex++;
               }
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