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
        $html .= '<div><button type="submit" class="add-element btn btn-small btn-info" id="add_element_' . $element->id . '" name="add_element_' . $element->id . '"><i class="icon-plus-sign"></i> Add another</button></div>';
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
    
    protected function _displayPersonNameFields($record,$element,$extraFieldCount=null) {
        $html = '';
        if (array_key_exists('PersonNames', $_POST)) {
            
            $postArray = $_POST['PersonNames'];
            $newIndex = 0;
            if (!$extraFieldCount) {
                for ($i = 0; $i < $extraFieldCount; $i++) {
                    $postArray['new-'.$element->id] = array('index'=>$newIndex,'title'=>'','firstname'=>'','lastname'=>'','middlename'=>'','suffix'=>'','element_id'=>$element->id,'record_id'=>$record->id);
                    $newIndex++; 
                }
            }

            foreach ($postArray as $key => $personName) {
               $html .= '<div class="input-block" style="display:inline-block">';
               if (substr($key,0,3) == 'new') {
                   foreach($personName as $pname) {
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][title]',$pname['title'],$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'.  $element->id .']['. $newIndex .'][firstname]',$pname['firstname'],$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][middlename]',$pname['middlename'],$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][lastname]',$pname['lastname'],$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][suffix]',$pname['suffix'],$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)','style'=>'margin-right:1em;'));
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][record_id]" value="'. $record->id .'" />'; 
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][index]" value="'. $newIndex.'" />';
                    $html .= $this->_createRemoveButton() . '</div>';
                    $newIndex++;
                   }
               } else if (is_int($key)){ 
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $key .'][title]', $personName['title'],$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $key .'][firstname]', $personName['firstname'],$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $key .'][middlename]', $personName['middlename'],$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $key .'][lastname]', $personName['lastname'],$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames['. $key .'][suffix]', $personName['suffix'],$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)','style'=>'margin-right:1em;'));
                    $html .= '<input type="hidden" name="PersonNames['. $key .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames['. $key .'][record_id]" value="'. $record->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames['. $key .'][id]" value="'. $key .'" />';
                    $html .= $this->_createRemoveButton() . '</div>';
                    
                }  else {
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][title]','',$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][firstname]','',$options=array('class'=>'span2','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][middlename]','',$options=array('class'=>'span2','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][lastname]','',$options=array('class'=>'span2','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][suffix]','',$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)','style'=>'margin-right:1em;'));
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][record_id]" value="'. $record->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][index]" value="'. $newIndex . '" />';  
                    $html .= $this->_createRemoveButton() . '</div>';
                    $newIndex++;
                }
                
                
            }   
            
            if ($extraFieldCount) {
                for ($i = 0; $i < $extraFieldCount; $i++) {
                    $html .= '<div class="input-block">';
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][title]','',$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][firstname]','',$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][middlename]','',$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][lastname]','',$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][suffix]','',$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)','style'=>'margin-right:1em;'));
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][record_id]" value="'. $record->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][index]" value="'. $newIndex . '" />'; 
                    $html .= $this->_createRemoveButton();
                    $html .= '</div>';
                    $newIndex++;
                }
            }
        } else {
            $pn = new PersonName;
            $personNames = $pn->getPersonNamesByRecordAndElementIds($record->id,$element->id);
            if (count($personNames) < 1) {
                $blankPerson = new PersonName();
                $blankPerson['id'] = 'new-'.$element->id;
                $personNames[] = $blankPerson;
            }
            foreach ($personNames as $personName) {
                if (substr($personName['id'], 0, 3) == 'new') {
                    $index = '[0]';
                } else {
                    $index = null;
                }
                $html .= '<div class="input-block">';
                $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName['id'] .']'.$index.'[title]', $personName['title'],$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName['id'] .']'.$index.'[firstname]', $personName['firstname'],$options=array('class'=>'input-small','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName['id'] .']'.$index.'[middlename]', $personName['middlename'],$options=array('class'=>'input-small','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName['id'] .']'.$index.'[lastname]', $personName['lastname'],$options=array('class'=>'input-small','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                $html .= $this->_displayPersonNameFormInput('PersonNames['. $personName['id'] .']'.$index.'[suffix]', $personName['suffix'],$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)'));
                $html .= '<input type="hidden" name="PersonNames['. $personName['id'] .']'.$index.'[element_id]" value="'. $element->id .'" />';
                $html .= '<input type="hidden" name="PersonNames['. $personName['id'] .']'.$index.'[record_id]" value="'. $record->id .'" />';
                $html .= $this->_createRemoveButton();
                $html .= '</div>';
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
    
    private function _createRemoveButton() {
        $html = ' <button type="submit" class="remove-element btn btn-small btn-danger" style="margin-top: -10px"><i class="icon-remove-sign"></i></button>';
        return $html; 
    }
    
    
}