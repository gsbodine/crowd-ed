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
    
    public function getStatusIdByName($statusName) {
        $status_id = $this->getDb()->getTable(EditStatus)->findBy($params=array('status'=>$statusName));
        return $status_id;
    }
    
}

?>
