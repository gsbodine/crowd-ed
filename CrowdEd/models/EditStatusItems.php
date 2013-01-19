<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of EditStatusItems
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class EditStatusItems extends Omeka_Record_AbstractRecord {
    
    public $edit_status_id;
    public $item_id;
    
    
    public function getItemEditStatus($item) {
        $status = $this->getDb()->getTable('EditStatusItems')->findBy($params=array('item_id'=>$item->id));
        if (is_array($status) && count($status) >= 1) {
            return $status[0];
        } else {
            return $status;
        }
        
    }
    
}

?>
