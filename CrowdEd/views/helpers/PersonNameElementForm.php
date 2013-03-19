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

    public function personNameElementForm(Element $element, Omeka_Record_AbstractRecord $record, $options = array(),$noRow=false) {
        $columnSpan = isset($options['columnSpan']) ? $options['columnSpan'] : 6;
        $extraFieldCount = isset($options['extraFieldCount']) ? $options['extraFieldCount'] : null;
        
        $this->_element = $element;
        $record->loadElementsAndTexts();
        $this->_record = $record;
        
        $rowClass = ' row';
        if ($noRow) {
            $rowClass = '';
        }
        
        $html = '<div class="field' . $rowClass . '" id="element-' . html_escape($element->id) . '">';
        $html .= $this->_displayFieldLabel();
        $html .= $this->_displayPersonNameFields($this->_record,$this->_element,$extraFieldCount); 
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
                    $postArray['new-'.$element->id] = array('index'=>$newIndex,'title'=>'','firstname'=>'','lastname'=>'','middlename'=>'','suffix'=>'','orgname'=>'','element_id'=>$element->id,'record_id'=>$record->id);
                    $newIndex++; 
                }
            }

            foreach ($postArray as $key => $personName) {
                $html .= '<div class="input-block">';
                $html .= '<span class="span6"><hr class="personForm"></span>';
                
                if (substr($key,0,3) == 'new') {
                   foreach($personName as $pname) {
                
                    $html .= '<div class="span3">';
                    $html .= '<label class="personNameLabel">Title: </label>'. $this->_displayPersonNameFormTitleSelect('PersonNames[new-'. $element->id .']['. $newIndex .'][title]',$pname['title'],$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">First name: </label>'. $this->_displayPersonNameFormInput('PersonNames[new-'.  $element->id .']['. $newIndex .'][firstname]',$pname['firstname'],$options=array('class'=>'span2','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Middle name: </label>'.$this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][middlename]',$pname['middlename'],$options=array('class'=>'span2','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Last name: </label>'. $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][lastname]',$pname['lastname'],$options=array('class'=>'span2','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Suffix: </label>'. $this->_displayPersonNameFormSuffixSelect('PersonNames[new-'. $element->id .']['. $newIndex .'][suffix]',$pname['suffix'],$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)','style'=>'margin-right:1em;'));
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][record_id]" value="'. $record->id .'" />'; 
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][index]" value="'. $newIndex.'" />';
                    $html .= '</div>';
                    
                    // org name field, new column
                    $html .= '<div class="span3">';
                    $html .= '<label class="institutionNameLabel">Organization (Group, Company, or Institution)</label>';
                    $html .= $this->_createOrgField('PersonNames[new-'. $element->id .']['. $newIndex .'][orgname]','',$options=array('class'=>'span3','placeholder'=>'e.g. Berry Alumni Association'));
                    $html .= $this->_createRemoveButton();
                    $html .= '</div>';
                    
                    $newIndex++;
                   }
               } else if (is_int($key)){
                    $html .= '<div class="span3">';
                    $html .= '<label class="personNameLabel">Title: </label>'. $this->_displayPersonNameFormTitleSelect('PersonNames['. $key .'][title]', $personName['title'],$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">First name: </label>'. $this->_displayPersonNameFormInput('PersonNames['. $key .'][firstname]', $personName['firstname'],$options=array('class'=>'span2','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Middle name: </label>'.$this->_displayPersonNameFormInput('PersonNames['. $key .'][middlename]', $personName['middlename'],$options=array('class'=>'span2','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Last name: </label>'. $this->_displayPersonNameFormInput('PersonNames['. $key .'][lastname]', $personName['lastname'],$options=array('class'=>'span2','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Suffix: </label>'. $this->_displayPersonNameFormSuffixSelect('PersonNames['. $key .'][suffix]', $personName['suffix'],$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)','style'=>'margin-right:1em;'));
                    $html .= '<input type="hidden" name="PersonNames['. $key .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames['. $key .'][record_id]" value="'. $record->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames['. $key .'][id]" value="'. $key .'" />';
                    $html .= '</div>';
                    // org name field, new column
                    $html .= '<div class="span3">';
                    $html .= '<label class="institutionNameLabel">Organization (Group, Company, or Institution)</label>';
                    $html .= $this->_createOrgField('PersonNames['. $personName['id'] .']'.$index.'[orgname]', $personName['orgname'],$options=array('class'=>'span3','placeholder'=>'e.g. Berry Alumni Association'));
                    $html .= $this->_createRemoveButton();
                    $html .= '</div>';
                
               }  else {
                    $html .= '<div class="span3">';
                    $html .= '<label class="personNameLabel">Title: </label>'. $this->_displayPersonNameFormTitleSelect('PersonNames[new-'. $element->id .']['. $newIndex .'][title]','',$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">First name: </label>'. $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][firstname]','',$options=array('class'=>'span2','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Middle name: </label>'.$this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][middlename]','',$options=array('class'=>'span2','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Last name: </label>'. $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][lastname]','',$options=array('class'=>'span2','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Suffix: </label>'. $this->_displayPersonNameFormSuffixSelect('PersonNames[new-'. $element->id .']['. $newIndex .'][suffix]','',$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)','style'=>'margin-right:1em;'));
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][record_id]" value="'. $record->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][index]" value="'. $newIndex . '" />';  
                    $html .= '</div>';
                    
                    // org name field, new column
                    $html .= '<div class="span3">';
                    $html .= '<label class="institutionNameLabel">Organization (Group, Company, or Institution)</label>';
                    $html .= $this->_createOrgField('PersonNames[new-'. $element->id .']['. $newIndex .'][orgname]','',$options=array('class'=>'span3','placeholder'=>'e.g. Berry Alumni Association'));
                    $html .= $this->_createRemoveButton();
                    $html .= '</div>';
                    
                    $newIndex++;
                }
                $html .= '</div>'; // end .input-block
            }   
            
            if ($extraFieldCount) {
                for ($i = 0; $i < $extraFieldCount; $i++) {
                    $html .= '<div class="input-block"><span class="span6"><hr class="personForm"></span>';
                    
                    $html .= '<div class="span3">';
                    $html .= '<label class="personNameLabel">Title: </label>'. $this->_displayPersonNameFormTitleSelect('PersonNames[new-'. $element->id .']['. $newIndex .'][title]','',$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">First name: </label>'. $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][firstname]','',$options=array('class'=>'span2','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Middle name: </label>'.$this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][middlename]','',$options=array('class'=>'span2','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Last name: </label>'. $this->_displayPersonNameFormInput('PersonNames[new-'. $element->id .']['. $newIndex .'][lastname]','',$options=array('class'=>'span2','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                    $html .= '<label class="personNameLabel">Suffix: </label>'. $this->_displayPersonNameFormSuffixSelect('PersonNames[new-'. $element->id .']['. $newIndex .'][suffix]','',$options=array('class'=>'span1','placeholder'=>'Suffix (e.g. Jr.)','style'=>'margin-right:1em;'));
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][element_id]" value="'. $element->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][record_id]" value="'. $record->id .'" />';
                    $html .= '<input type="hidden" name="PersonNames[new-'. $element->id .']['. $newIndex .'][index]" value="'. $newIndex . '" />';  
                    $html .= '</div>';
                    
                    // org name field, new column
                    $html .= '<div class="span3">';
                    $html .= '<label class="institutionNameLabel">Organization (Group, Company, or Institution)</label>';
                    $html .= $this->_createOrgField('PersonNames[new-'. $element->id .']['. $newIndex .'][orgname]','',$options=array('class'=>'span3','placeholder'=>'e.g. Berry Alumni Association'));
                    $html .= $this->_createRemoveButton();
                    $html .= '</div>';
                    
                    $html .= '</div>'; // end .input-block
                    $newIndex++;
                }
            }
        } else {
            // load the fields for person names on page load:
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
                
                $html .= '<div class="input-block"><span class="span6"><hr class="personForm"></span>';
                $html .= '<div class="span3">';
                $html .= '<label class="personNameLabel">Title: </label>'. $this->_displayPersonNameFormTitleSelect('PersonNames['. $personName['id'] .']'.$index.'[title]', $personName['title'],$options=array('class'=>'span1','placeholder'=>'Title','style'=>'margin-right:1em;'));
                $html .= '<label class="personNameLabel">First name: </label>'. $this->_displayPersonNameFormInput('PersonNames['. $personName['id'] .']'.$index.'[firstname]', $personName['firstname'],$options=array('class'=>'span2','placeholder'=>'First Name','style'=>'margin-right:1em;'));
                $html .= '<label class="personNameLabel">Middle name: </label>'. $this->_displayPersonNameFormInput('PersonNames['. $personName['id'] .']'.$index.'[middlename]', $personName['middlename'],$options=array('class'=>'span2','placeholder'=>'Middle Name','style'=>'margin-right:1em;'));
                $html .= '<label class="personNameLabel">Last name: </label>'. $this->_displayPersonNameFormInput('PersonNames['. $personName['id'] .']'.$index.'[lastname]', $personName['lastname'],$options=array('class'=>'span2','placeholder'=>'Last Name','style'=>'margin-right:1em;'));
                $html .= '<label class="personNameLabel">Suffix: </label>'. $this->_displayPersonNameFormSuffixSelect('PersonNames['. $personName['id'] .']'.$index.'[suffix]', $personName['suffix'],$options=array('class'=>'span1','placeholder'=>'(e.g. Jr.)'));
                $html .= '<input type="hidden" name="PersonNames['. $personName['id'] .']'.$index.'[element_id]" value="'. $element->id .'" />';
                $html .= '<input type="hidden" name="PersonNames['. $personName['id'] .']'.$index.'[record_id]" value="'. $record->id .'" />';
                $html .= '</div>';
                // org name field, new column
                $html .= '<div class="span3">';
                $html .= '<label class="institutionNameLabel">Organization (Group, Company, or Institution)</label>';
                $html .= $this->_createOrgField('PersonNames['. $personName['id'] .']'.$index.'[orgname]', $personName['orgname'],$options=array('class'=>'span3','placeholder'=>'e.g. Berry Alumni Association'));
                $html .= $this->_createRemoveButton();
                $html .= '</div>';
                $html .= '</div>'; // end .input-block
            }
        }
        //$html .= '</div>'; //end row
        $html .= $this->_getAddFieldButton();
       // $html .= '</div>'; // end container div (.span6, .field)
        return $html;
        
    }
    
    private function _createOrgField($inputNameStem, $value, $options) {
        $html = '';
        $html .= $this->view->formText($inputNameStem, $value, $options);
        return $html;
    }
    
    private function _displayPersonNameFormInput($inputNameStem, $value, $options=array()) {
        
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '3';  
        $html = '';
        $html .= $this->view->formText($inputNameStem, $value, $options);
        return $html;
    }
    
    private function _displayPersonNameFormTitleSelect($inputNameStem, $value, $options=array()) {
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '2'; 
        $html = '';
        // todo: refactor - this is ugly, unilingual, and surely incomplete. but given the schedule and our purpose, this will work for now - gsb
        $selectOptions = array(''=>'','Dr.'=>'Dr.','Miss'=>'Miss','Mr.'=>'Mr.','Mrs.'=>'Mrs.','Prof.'=>'Prof.','Rev.'=>'Rev.');
        $html .= $this->view->formSelect($inputNameStem, trim($value), $options, $selectOptions);
        return $html;
    }
    
    private function _displayPersonNameFormSuffixSelect($inputNameStem, $value, $options=array()) {
        $fieldColumnSpan = isset($options['fieldColumnSpan']) ? $options['fieldColumnSpan'] : '2'; 
        $html = '';
        // todo: refactor - same as above: this is ugly, unilingual, and surely incomplete. - gsb
        $selectOptions = array(''=>'','Jr.'=>'Jr.','II'=>'II','III'=>'III','IV'=>'IV','V'=>'V','VI'=>'VI','M.D.'=>'M.D.','Esq.'=>'Esq.','M.A.'=>'M.A.','M.S.'=>'M.S.','Ph.D.'=>'Ph.D.');
        $html .= $this->view->formSelect($inputNameStem, trim($value), $options, $selectOptions);
        return $html;
    }
    
    protected function _displayFieldLabel() {
        $html = '<div class="span6 person-name-set-label">';
        // todo: genericize - MBDA-specific relabeling - gsb
        switch ($this->_getFieldLabel()) {
            case 'Creator':
                $html .= '<i class="icon-user"></i> ';
                break;
            case 'Recipient':
                $html .= '<i class="icon-envelope"></i> ';
                break;
        }
        if ($this->_getFieldLabel() == 'Creator') {
            $label = 'Author(s)';
        } elseif ($this->_getFieldLabel() == 'Recipient') {
            $label = 'Recipient(s)';
        } else {
            $label = $this->_getFieldLabel();
        }
        $html .=  __($label);
        $html .= $this->_displayExplanation();
        $html .= '</div>';
        return $html;
    }
    
    private function _getAddFieldButton() {
        $html = '';
        $person = '';
        switch ($this->_getFieldLabel()) {
            case 'Creator':
                $person = 'author';
                break;
            case 'Recipient':
                $person = 'recipient';
                break;
            default: 
                $person = 'field';
        }
        $html .= '<span class="span6"><div class="well well-small text-center" style="margin-top: 20px"><button type="submit" class="add-element btn btn-small btn-info" id="add_element_' . $this->_element->id . '" name="add_element_' . $this->_element->id . '"><i class="icon-plus-sign"></i> Add another ' . $person .'</button></div></span>';
        return $html;
    }
    
    protected function _displayExplanation() {
        $html = ' <a href="#" rel="tooltip" class="tooltipper" title="';
        $html .= $this->_getFieldDescription() .'"><i class="icon-info-sign"></i></a>';

        return $html;
    }
    
    private function _createRemoveButton() {
        $person = '';
        switch ($this->_getFieldLabel()) {
            case 'Creator':
                $person = 'author';
                break;
            case 'Recipient':
                $person = 'recipient';
                break;
            default: 
                $person = 'field';
        }
        $html = '<span><button type="submit" class="remove-element btn btn-small btn-danger" style="margin: 1em 0;"><i class="icon-remove-sign"></i> Remove this ' . $person . '</button></span>';
        return $html; 
    }
    
    
}