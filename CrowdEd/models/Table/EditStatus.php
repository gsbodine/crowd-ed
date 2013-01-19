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
class Table_EditStatus extends Omeka_Db_Table {
    
    protected function _getColumnPairs() {
        return array('edit_statuses.id', 'edit_statuses.status');
    }
    
}

?>
