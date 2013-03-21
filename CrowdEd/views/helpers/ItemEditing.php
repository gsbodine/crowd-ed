<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of Items
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_View_Helper_ItemEditing extends Zend_View_Helper_Abstract {
    public function itemEditing() {
        return $this;
    }
    
    public function getRandomUneditedItemLink() {
        
    }
    
    public function getRandomUneditedItem($db) {
        $itemIds = $this->_getUneditedItemIds($db);
        $randItem = array_rand($itemIds);
        $item = get_db()->getTable('Item')->find($itemIds[$randItem]);
        return $item;
    }
    
    private function _getUneditedItemIds($db,$limit=null) {
        $select = new Omeka_Db_Select($db);
        $select->from(array('i'=>'items'), array('i.id'))
                ->joinLeft(array('esi'=>'edit_statuses_items'), "esi.item_id = i.id",array())
                ->joinLeft(array('es'=>'edit_statuses'), "es.id = esi.edit_status_id",array())
            ->where("es.status = 'Open' OR es.status is NULL")
            ->group('i.id')
            ->limit($limit);
        $stmt = $select->query()->fetchAll();
        foreach ($stmt as $row) {
            $items[] = $row['id'];
        }
        return $items;
    }
    
}

?>
