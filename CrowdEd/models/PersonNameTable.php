<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of PersonNameTable
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class PersonNameTable extends Omeka_Db_Table {
    
    /*protected $_alias = 'pers';


    public function findByElementTextId($elementTextId) {
        $select = $this->getSelect()->where('element_text_id = ?', (int)$elementTextId);
        return $this->fetchObject($select);
    }
    
    public function findForItem($includeAll = true) {
        return $this->getTable('PersonName')->findByRecordId($item->id);
    } */
    
    public function findByRecordId($id) {
        return $this->getTable('PersonName')->findBySql('record_id = ?',array($id), true);
    }
    
    
}

?>
