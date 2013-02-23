<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of ItemCitation
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_View_Helper_ItemCitation extends Zend_View_Helper_Abstract {
    public function itemCitation($item) {
        $creator    = trim(strip_formatting(metadata($item,array('Dublin Core', 'Creator'))));
        $title      = trim(strip_formatting(metadata($item,array('Dublin Core', 'Title'))));
        $siteTitle  = trim(strip_formatting(option('site_title')));
        $itemId     = metadata($item,'id');
        $accessDate = date('F j, Y');
        $uri        = html_escape(record_url($item,'show',true));
        $siteEditor = trim(strip_formatting(get_theme_option('Site Editor')));
        $siteLocation = trim(strip_formatting(get_theme_option('Site Location')));
        $siteInstitution = trim(strip_formatting(get_theme_option('Site Institution')));
        $addlEditors = $this->_getUsersForCitation($item);

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
    
    private function _getUsersForCitation($item) {
        $select = new Omeka_Db_Select($item->_db);
        $select->from(array('e'=>'entities'), array('e.first_name','e.last_name','max(time)'))
                ->joinInner(array('u'=>'users'), "u.id = e.user_id", array())
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
}

?>
