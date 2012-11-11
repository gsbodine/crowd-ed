<?php

/**
 * Generate (and override) the form markup for entering element text metadata in Omeka.
 *
 * @package CrowdEd
 */

require_once APP_DIR . '/helpers/ElementForm.php';

class CrowdEd_View_Helper_ElementForm extends Omeka_View_Helper_ElementForm {
    
    protected $_element;
    protected $_record;

    public function elementForm(Element $element, Omeka_Record $record, $options = array()) {
        $extraFieldCount = isset($options['extraFieldCount']) ? $options['extraFieldCount'] : null;

        $columnSpan = isset($options['columnSpan']) ? $options['columnSpan'] : '6';
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '3';
        $this->_element = $element;
        $record->loadElementsAndTexts(); 
        $this->_record = $record;
        
        $personMixin = new PersonNameElementText($this->_record);
        $isPersonName = $personMixin->isPersonNameElement($element);
        
        $html = '';
        $html .= '<div class="field span'. $columnSpan .'" id="element-' . html_escape($element->id) . '">';
        $html .= $this->_displayFieldLabel();
        $html .= $this->_displayValidationErrors();
        if ($isPersonName){
            $elementTexts = $this->_record->getTextsByElement($this->_element);
            if ($elementTexts) {
                foreach ($elementTexts as $key => $value) {
                    if ($value['element_id'] == $this->_element->id) {
                        $elementTextId = $value['id']; 
                        $html.= $this->_displayPersonNameFields($record->id,$element->id,$elementTextId,$extraFieldCount);
                    }
                }
            } else {
                $html .= $this->_displayPersonNameFields($record->id,'0', $extraFieldCount);
            }
            
        } else {
            $html .= $this->_displayFormFields($options=array('fieldColumnSpan'=>$fieldColumnSpan),$extraFieldCount); 
        }
        $html .= '</div>';
        
        return $html;
    }
    
    protected function _displayFormFields($options = array(), $extraFieldCount = null) {
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '3';    
        $fieldCount = $this->_getFormFieldCount() + (int) $extraFieldCount;
        $html = '';

        for ($i=0; $i < $fieldCount; $i++) {
            $fieldStem = $this->_getFieldNameStem($i);

            $html .= $this->_displayFormInput($fieldStem, $this->_getValueForField($i),$options=array('fieldColumnSpan'=>$fieldColumnSpan));
            $html .= $this->_displayFormControls();
        }
        return $html;
    }
    
    protected function _displayPersonNameFields($extraFieldCount = NULL) {
        $fieldCount = $this->_getFormFieldCount() + (int) $extraFieldCount;
        $html = '';
        
        
        for ($i=0; $i < $fieldCount; $i++) {
            $fieldStem = $this->_getFieldNameStem($i);
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][title]', $personName->title,$options=array('class'=>'span1','placeholder'=>'Title'));
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][firstname]', $personName->firstname,$options=array('class'=>'span2','placeholder'=>'First Name'));
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][middlename]', $personName->middlename,$options=array('class'=>'span2','placeholder'=>'Middle Name'));
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][lastname]', $personName->lastname,$options=array('class'=>'span2','placeholder'=>'Last Name'));
            $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName->id .'][suffix]', $personName->suffix,$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)'));
            //$html .= '<input type="hidden" name="PersonNames['. $personName->id .'][element_text_id]" value="'.  .'" />';
            //$html .= 'Record ID ='.$this->_record->id;
            $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][element_id]" value="'. $element_id .'" />';
            $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][element_text_id]" value="'. $elementTextId .'" />';
            $html .= '<input type="hidden" name="PersonNames['. $personName->id .'][record_id]" value="'. $record_id .'" />';
            
            $html .= $this->_displayFormControls();
        }
        
        return $html;
    }
    
    protected function _displayPersonNameFormInput($inputNameStem, $value, $options=array()) {
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '3';    
        $fieldDataType = $this->_getElementDataType();
        $elementName = $this->_element['name'];
        $html = '';
        $html .= $this->view->formText($inputNameStem, $value, $options);
        return $html;
    }
    
    protected function _displayFormInput($inputNameStem, $value, $options=array()) {
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '3';    
        $fieldDataType = $this->_getElementDataType();
        $elementName = $this->_element['name'];
        
        // Plugins should apply a filter to this blank HTML in order to display it in a certain way.
        $html = '';
        $filterName = $this->_getPluginFilterForFormInput();

        $html = apply_filters($filterName, $html, $inputNameStem, $value, $options, $this->_record, $this->_element);

        // Short-circuit the default display functions b/c we already have the HTML we need.
        if (!empty($html)) {
            return $html;
        }
        
        // Create a form input based on the element type name
        switch ($fieldDataType) {
            case 'Tiny Text':
                return $this->view->formText(
                    $inputNameStem . '[text]',
                    $value,
                    //array());
                    array('class'=>'span'.$fieldColumnSpan));
                break;
            case 'Text':
                return $this->view->formTextarea(
                    $inputNameStem . '[text]',
                    $value,
                    array('class'=>'span'.$fieldColumnSpan, 'rows'=>5));
                break;
            case 'Date':
                return $this->view->_dateField(
                    $inputNameStem,
                    $value,
                    array());
                break;
            case 'Date Range':
                return $this->_dateRangeField(
                    $inputNameStem,
                    $value,
                    array());
            case 'Integer':
                return $this->view->formText(
                    $inputNameStem . '[text]',
                    $value,
                    array('class' => 'span'.$formColumnSpan, 'size' => 40));
            case 'Date Time':
                return $this->_dateField(
                    $inputNameStem,
                    $value,
                    array());
            default:
                throw new Exception(__('Cannot display a form input for "%s" if element type name is not given!', $element['name']));
                break;
        }
    }
    
    protected function _displayFieldLabel() {
        $html = '<div class="form-inline"><label>';
        switch ($this->_getFieldLabel()) {
            case 'Date': 
                $html .= '<i class="icon-calendar"></i> ';
                break;
            case 'Type':
                $html .= '<i class="icon-file"></i> ';
                break;
            case 'Script Type':
                $html .= '<i class="icon-pencil"></i> ';
                break;
            case 'Creator':
                $html .= '<i class="icon-user"></i> ';
                break;
            case 'Recipient':
                $html .= '<i class="icon-envelope"></i> ';
                break;
            case 'Flag for Review':
                $html .= '<i class="icon-flag"></i> ';
        }
       // endswitch;
        $html .=  __($this->_getFieldLabel()) . '</label>';
        $html .= $this->_displayExplanation();
        $html .= '</div>';
        return $html;
    }
    
    protected function _displayExplanation() {
        $html = ' <a href="#" rel="tooltip" class="tooltipper" title="';
        $html .= $this->_getFieldDescription() .'"><i class="icon-info-sign"></i></a>';

        return $html;
    }
    
    protected function _dateField($inputNameStem,$value,$options = array()) {
        $list = explode('-', $value);
        
        if (count($list) == 3) {
            $year = $list[0];
            $month = $list[1];
            $day = $list[2];
        } else if (count($list) == 2) {
            $year = '';
            $month = $list[0];
            $day = $list[1];
        } else if (count($list) == 1 && strlen($list[0]) == 4){
            $year = $list[0];
            $month = '';
            $day = '';
        } else {
            $year = '';
            $month = $list[0];
            $day = '';
        }
        //$html = '<div>value: ' . $value . '<br />list count: ' . count($list) . '<br /> first list item: ' . $list[0] . '</div>';
        $html = '<div class="form-inline dateinput">';
        //$html .= ' Month: ' . $this->view->formText($inputNameStem . '[month]', $month, array('class'=>'textinput input-mini', 'maxlength'=>'2'));
        $html .= 'Month: ' . $this->view->formSelect($inputNameStem . '[month]',$month, array('class'=>'input-medium'), array('' => '','1'=>'January','2'=>'February'));
        $html .= ' Day: ' . $this->view->formSelect($inputNameStem . '[day]', $day, array('class'=>'textinput input-mini'), array(''=>'','1'=>'1','31'=>'31'));
        $html .= ' Year: ' . $this->view->formSelect($inputNameStem . '[year]', $year, array('class'=>'textinput input-small'), array(''=>'','2000'=>'2000','1928'=>'1928'));

        $html .= '</div>';
        return $html;
    }
    
    public function getPersonNamesByElementTexts($index=null) {
        $personNames = $this->_record->getPersonNames($this->_element);
        if ($index !== null) {
            if (array_key_exists($index, $texts)) {
                return $texts[$index];
            } else {
                return null;
            }
        }
        return $texts;
    }
}