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

function getMostRecentEditors($db,$limit=10) {
    $select = new Omeka_Db_Select($db);
    $select->from(array('u'=>'users'), array('u.username','max(time)'))
            ->joinInner(array('e'=>'entities'), "e.id = u.entity_id", array())
            ->joinInner(array('er'=>'entities_relations'), "er.entity_id = e.id",array())
            ->joinInner(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
        ->where("time <> '0000-00-00 00:00:00'")
        ->group('u.username')
        ->order('max(time) DESC');
    $stmt = $select->query();
    while ($row = $stmt->fetch()) {
        $html .= '<li><span class="label"><i class="icon-user"></i> '. $row['username'] .'</span></li>';
    }
    
    return $html;

}

function getUsersForCitation($item) {
    $select = new Omeka_Db_Select($item->_db);
    $select->from(array('u'=>'users'), array('e.first_name','e.last_name','max(time)'))
            ->joinInner(array('e'=>'entities'), "e.id = u.entity_id", array())
            ->joinInner(array('er'=>'entities_relations'), "er.entity_id = e.id",array())
            ->joinInner(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
        ->where("(time <> '0000-00-00 00:00:00') and (first_name != '') and (last_name != '') and (u.username != 'sschlitz') and (relation_id = ?)",$item->id)
        ->group('u.username')
        ->order('max(time) DESC');
    $stmt = $select->query();
    $html = '';
    while ($row = $stmt->fetch()) {
        $html .= ', '.$row['first_name'].' '.$row['last_name'];
    }
    
    return $html;
}

function crowded_item_citation($cite=null,$item) {
    // this will likely be moved into Crowd-Ed, but this will allow for the configuration via the theme configurator :)
    if(!$item) {
        $item = get_current_item();
    }

    $creator    = trim(strip_formatting(item('Dublin Core', 'Creator', array(), $item)));
    $title      = trim(strip_formatting(item('Dublin Core', 'Title', array(), $item)));
    $siteTitle  = trim(strip_formatting(settings('site_title')));
    $itemId     = item('id', null, array(), $item);
    $accessDate = date('F j, Y');
    $uri        = html_escape(abs_item_uri($item));
    $siteEditor = trim(strip_formatting(get_theme_option('Site Editor')));
    $siteLocation = trim(strip_formatting(get_theme_option('Site Location')));
    $siteInstitution = trim(strip_formatting(get_theme_option('Site Institution')));
    $addlEditors = getUsersForCitation($item);

    $itemDate = date_format(date_create($item->added),'Y');
    
    $cite = '';
    if ($creator) {
        $cite .= "$creator, ";
    }
    if ($title) {
        $cite .= "&#8220;$title.&#8221; ";
    }
    if ($siteTitle) {
        $cite .= "<em>$siteTitle</em>. ";
    }
    if ($siteEditor) {
        $cite .= "Eds. $siteEditor$addlEditors, et al. ";
    }
    if ($siteLocation) {
        $cite .= "$siteLocation";
    }
    if ($siteLocation && $siteInstitution) {
        $cite .= ": ";
    }
    if ($siteInstitution) {
        $cite .= "$siteInstitution";
    }
    if ($siteInstitution && $itemDate) {
        $cite .= ", ";
    }
    if ($itemDate) {
        $cite .= " $itemDate";
    }
    $cite .= ". accessed $accessDate, ";
    $cite .= "$uri.";

    return $cite;

}

?>
