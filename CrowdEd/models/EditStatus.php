<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of EditStatus
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

class EditStatus extends Omeka_Record_AbstractRecord {
    
    public $id;
    public $status;
    public $description;
    public $isLockedStatus;
    
    public function getStatusIdByName($statusName) {
        $status_id = $this->getDb()->getTable(EditStatus)->findBy($params=array('status'=>$statusName));
        return $status_id;
    }
    
    public function getStatusNameById($status_id) {
        $statusName = $this->getDb()->getTable('EditStatus')->find($status_id);
        return $statusName;
    }
    
    public function getLockedStatus($status_id=null) {
        if ($status_id == null || $status_id == '') {
            return;
        } else {
            $status = $this->getDb()->getTable('EditStatus')->find($status_id);
            $editStatus = $status->isLockedStatus;
            return $editStatus;
        }
    }
    
}

?>
