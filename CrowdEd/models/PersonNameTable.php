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
    
    public function findByRecordId($id) {
        return $this->getTable('PersonName')->findBySql('record_id = ?',array($id), true);
    }
    
    
}

?>
