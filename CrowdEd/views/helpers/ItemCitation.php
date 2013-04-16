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
        $addlEditors = $this->_formatUsersForCitation($item);

        $itemDate = date_format(date_create($item->added),'Y');

        $cite = '<p>';
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
        $cite .= "$uri.</p>";
        
        $cite .= '<p class="pull-right"><small><i class="icon-book"></i> <a href="#citationModal" role="button" data-toggle="modal">Item Edit History</a></small></p>';
        
        $cite .= $this->_makeUserCitationModal($item);

        return $cite;
    }
    
    private function _formatUsersForCitation($item) {
        $html = '';
        $users = $this->_getUsersForCitation($item);
        if ($users) {
            foreach ($users as $citeName) {
                $html .= ', '.$citeName['first_name'].' '.$citeName['last_name'];
            }  
        }
        return $html;
    }
    
    private function _formatUsersForHistory($item) {
        $html = '';
        $users = $this->_getUsersForCitation($item);
        if ($users) {
            foreach ($users as $u) {
                $html .= '<p><i class="icon-edit"></i> <strong>'. $u['first_name']. ' ' .$u['last_name']. '</strong> (<a href="/participate/profile/'. $u['id'] .'">' . $u['username'] .'</a>): '. date("M j, Y - g:i:s a",strtotime($u['time'])) .'</p>';
            }
        } else {
            $html = '<p class="alert alert-warning">No one else has edited this item yet.</p>';
        }
        return $html;
    }
    
    private function _getUsersForCitation($item) {
        $select = new Omeka_Db_Select($item->_db);
        $select->from(array('e'=>'entities'), array('e.first_name','e.last_name','max(time) as time','u.username','u.id'))
                ->joinInner(array('u'=>'users'), "u.id = e.user_id", array())
                ->joinInner(array('er'=>'entities_relations'), "er.entity_id = e.id",array())
                ->joinInner(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
            ->where("(time <> '0000-00-00 00:00:00') and (first_name != '') and (last_name != '') and (u.username != 'sschlitz') and (relation_id = ?) and e.private != 1",$item->id)
            ->group('u.username')
            ->order('max(time) DESC');
        $stmt = $select->query();
        $users = array();
        while ($row = $stmt->fetch()) {
            $users[] = array('first_name'=>$row['first_name'],'last_name'=>$row['last_name'],'username'=>$row['username'],'time'=>$row['time'],'id'=>$row['id']);
        } 
        return $users;
    }
    
    private function _makeUserCitationModal($item) {
        $html = '<div id="citationModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="citationModalLabel" aria-hidden="true">';
        $html .= '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="citationModalLabel"><i class="icon-book"></i> Item Editing History</h3></div>';
        $html .= '<div class="modal-body">'. $this->_formatUsersForHistory($item) .'</div>';
        $html .= '<div class="modal-footer"><button class="btn btn-success" data-dismiss="modal" aria-hidden="true"><i class="icon-ok"></i> OK</button></div>';
        $html .= '</div>';
        
        return $html;
    }
    
}

?>