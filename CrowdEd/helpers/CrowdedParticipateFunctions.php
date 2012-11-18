<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

function displayLastItemsEditedByUser($user,$numberOfItems=5) {
    $itemList = getItemsEditedByUser($entity_id,$numberOfItems);
    $html = '<ul class="unstyled">';
    $html .= $itemList;
    $html .= '</ul>';
    return $html;
}

function getItemsEditedByUser($user,$limit) {
    
    $select = new Omeka_Db_Select($user->_db);
    $select->from(array('er'=>'entities_relations'),'i.id')
            ->joinInner(array('e'=>'entities'), "e.id = er.entity_id", array())
            ->joinInner(array('i'=>'items'), "i.id = er.relation_id",array())
            ->joinInner(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
        ->where("ers.name='modified' and e.id = '$user->entity_id'")
        ->group('i.id')
        ->order('time DESC');
    $stmt = $select->query();
    $html = '';
    while ($row = $stmt->fetch()) {
        $html .= '<li><span class="label"><i class="icon-edit"></i> '. $row['id'] .'</span></li>';
    }
    return $html;
    
}

function featureUnavailable($alertClass='icon-warning') {
    $html = '<div class="alert alert-warning"><h4><i class="icon-asterisk"></i> Sorry!</h4><p>This feature is not yet available.</p></div>';

    return $html;
}

?>
