<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of Flagged
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_View_Helper_Flagged extends Zend_View_Helper_Abstract {
    public function flagged() {
        return $this;
    }
    
    public function getFlaggedItems() {
        
    }
    
    private function _getFlaggedItems() {
        $select = new Omeka_Db_Select($db);
        $select->from(array('er'=>'entities_relations'), array('i.id','count(i.id) as faves'))
                ->join(array('e'=>'entities'), "er.entity_id = e.id",array())
                ->join(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
                ->join(array('i'=>'items'), "i.id = er.relation_id")
            ->where("ers.name='favorite'")
            ->group('i.id')
            ->order('count(i.id) DESC')
            ->limit($limit);;
        $stmt = $select->query();
        while ($row = $stmt->fetch()) {
            $item = get_db()->getTable('Item')->find($row['id']);
            $items[] = array('item'=>$item,'faves'=>$row['faves']);
         }
        return $items;
    }
    
    
}

?>
