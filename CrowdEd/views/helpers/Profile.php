<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of Profile
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_View_Helper_Profile extends Zend_View_Helper_Abstract {
    public function profile() {
        return $this;
    }
    
    public function displayLastItemsEditedByUser($user,$numberOfItems=5) {
        $itemList = getItemsEditedByUser($entity_id,$numberOfItems);
        $html = '<ul class="unstyled">';
        $html .= $itemList;
        $html .= '</ul>';
        return $html;
    }

    public function getItemsEditedByUser($user,$limit=5) {
        $html = $this->_selectUserItems();
        return $html;
    }
    
    public function getUserFavorites($limit=5) {
        $html = $this->_selectUserItems('favorite', 'icon-heart', 'label label-important');
        return $html;
    }

    public function featureUnavailable($alertClass='icon-warning') {
        $html = '<div class="alert alert-warning"><h4><i class="icon-asterisk"></i> Sorry!</h4><p>This feature is not yet available.</p></div>';

        return $html;
    }
    
/* PRIVATE FUNCTIONS */
    private function _selectUserItems($relationshipName='modified',$icon='icon-user',$class='label',$metadata=array('Dublin Core','Title'),$limit=5) {
        $user = current_user();
        $entity = new Entity;
        $entity = $entity->getEntityByUserId($user->id);
        $select = new Omeka_Db_Select($user->_db);
        $select->from(array('er'=>'entities_relations'),array())
                ->joinInner(array('e'=>'entities'), "e.id = er.entity_id", array())
                ->joinInner(array('i'=>'items'), "i.id = er.relation_id",array('item_id'=>'i.id'))
                ->joinInner(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
            ->where("ers.name='$relationshipName' and e.id = '$entity->id'")
            ->group('i.id')
            ->order('time DESC')
            ->limit($limit);
        $stmt = $select->query()->fetchAll();
        $html = '';
        foreach ($stmt as $row) {
            $item = get_db()->getTable('Item')->find($row['item_id']);
            $html .= '<li><a href="' . url('/items/show/'.$row['item_id']) . '"><span class="' . $class . '"><i class="' . $icon . '"></i> '. metadata($item, $metadata) .'</span></a></li>';
        }
        return $html;
    }
    
}

?>
