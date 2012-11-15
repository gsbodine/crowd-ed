<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

function createCompletionMeter() {
    $totalItems = total_items();
    $undoneItems = count(get_items(array('tags'=>'TBE'),0));
    $doneItems = $totalItems - $undoneItems;

    $completionPercent = $doneItems / $totalItems * 100;
    $completionPercent = number_format($completionPercent);
    
    $html = '<div><small>'. $doneItems .' Edited Documents</small>';
    $html .= '<small class="pull-right">'. $totalItems .' Documents in Collection</small></div>';
    $html .= '<div class="progress progress-striped progress-success active"><div class="bar" style="width: '; 
    $html .= $completionPercent;
    $html .= '%;"></div></div>';
    return $html;
        
}

function getEditorsByVolume($db,$limit=10){
    
    $select = new Omeka_Db_Select($db);

    $select->from(array('u'=>'users'), array('u.username', 'count(u.username)'))
            ->joinInner(array('e'=>'entities'), "e.id = u.entity_id", array())
            ->joinInner(array('er'=>'entities_relations'), "er.entity_id = e.id", array())
            ->joinInner(array('ers'=>'entity_relationships'), "er.relationship_id = ers.id",array())
            ->group('u.username')
            ->order('count(u.username) DESC');
    
    $stmt = $select->query();
    while ($row = $stmt->fetch()) {
        $html .= '<li><span class="label label-inverse"><i class="icon-user"></i> '. $row['username'] .'</span></li>';
    }
    return $html;
}

function getMostRecentEditors($limit=10) {
    
}

?>
