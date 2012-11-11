<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of PersonNameElementText
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */


class PersonNameElementText extends ActsAsElementText {
    
    protected $_personNamesOnForm = array();
    
    public function isPersonNameElement($element){
        $elementIsPersonNameType = $this->_record->getTable('PersonNamesElements')->findBySql('element_id = ?',array($element->id));
        if ($elementIsPersonNameType) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getPersonNames($item) {
        return $this->_record->getTable('PersonName')->findBySql('record_id = ?',array($item->id));
    }
    
    public function getPersonNamesByRecordId($record_id) {
        return $this->_record->getTable('PersonName')->findBySql('record_id = ?',array($record_id));
    }
    
    public function getPersonNamesByRecordAndElementIds($record_id,$element_id) {
        return $this->_record->getTable('PersonName')->findBySql('record_id = ? AND element_id = ?',array($record_id,$element_id));
    }
    
}

?>
