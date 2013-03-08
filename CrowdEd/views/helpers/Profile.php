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

    public function getItemsEditedByUser($user,$limit=null) {
        $html = $this->_selectUserItems($user,'modified','icon-ok-circle','text-info',array('Dublin Core','Title'),$limit);
        return $html;
    }
    
    public function getUserEditedItemsAsTable($user,$limit=null) {
        $html = $this->_selectUserItems($user,'modified','icon-ok-circle','text-success',array('Dublin Core','Title'),$limit,$type='table');
        return $html;
    }
    
    public function getUserFavorites($user,$limit=null) {
        $html = $this->_selectUserItems($user,'favorite','icon-heart','text-error',array('Dublin Core','Title'),$limit,$type='list');
        return $html;
    }
    
    public function getUserFavoritesAsTable($user,$limit=null) {
        $html = $this->_selectUserItems($user,'favorite','icon-heart','text-error',array('Dublin Core','Title'),$limit,$type='table');
        return $html;
    }

    public function featureUnavailable($alertClass='icon-warning') {
        $html = '<div class="alert alert-warning"><h4><i class="icon-asterisk"></i> Sorry!</h4><p>This feature is not yet available.</p></div>';

        return $html;
    }
    
/* PRIVATE FUNCTIONS */
    private function _selectUserItems($user,$relationshipName='modified',$icon='icon-circle',$class='label label-inverse',$metadata=array('Dublin Core','Title'),$limit=null,$type='list') {
        $formatter = get_view()->format();
        $entity = new Entity;
        $entity = $entity->getEntityByUserId($user->id);
        $select = new Omeka_Db_Select($user->_db);
        $select->from(array('er'=>'entities_relations'),array())
                ->joinLeft(array('e'=>'entities'), "e.id = er.entity_id", array('er.time'))
                ->joinLeft(array('i'=>'items'), "i.id = er.relation_id",array('item_id'=>'i.id'))
                ->joinLeft(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
            ->where("ers.name='$relationshipName' and e.id = '$entity->id'")
            ->group('i.id')
            ->order('time DESC')
            ->limit($limit);
        $stmt = $select->query()->fetchAll();
        $html = '';
        
        foreach ($stmt as $row) {
            $item = get_db()->getTable('Item')->find($row['item_id']);
            if ($type == 'table') {
                $html .= '<tr><td>'. link_to_item(item_image('square_thumbnail', array(), 0, $item), array('class' => 'img'), 'show', $item) .'</td><td><p class="lead">'. link_to_item(metadata($item, array('Dublin Core','Title')), array('class' => 'title'), 'show', $item).'</p><p class="well">'. metadata($item, array('Dublin Core','Description')) .'</p><p>'. metadata($item,'citation',array('no_escape' => true)) .'</p></td><td><p class="text-center"><b><i class="icon-calendar"></i> &ndash; '. date("M j, Y",strtotime($row['time'])). '<br /><i class="icon-time"></i> &ndash; '. date("g:i:s a",strtotime($row['time'])) .'</b></p></td></tr>';
            } else {
                $html .= '<li class="user-list-item"><strong><a href="' . url('/items/show/'.$row['item_id']) . '"><span class="' . $class . '"><i class="' . $icon . '"></i> '. metadata($item, $metadata) .'</span></a></strong> &ndash; (' . $formatter->time_passed(strtotime($row['time'])) . ')</li>';
            }
        
        }   
        
        return $html;
    
    }

}
?>
