<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of CrowdEditors
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_View_Helper_CrowdEditors extends Zend_View_Helper_Abstract {
    public function CrowdEditors() {
       return $this;
    }
    
    public function getEditorsByVolume($db) {
        $stmt = $this->_getEditorsByVolumeQuery($db);
        $html = "";
        while ($row = $stmt->fetch()) {
            $html .= '<li><span class="text-info community-user-link"><strong><a href="/participate/profile/id/'. $row['id']. '">' . get_view()->gravatar($row['email'],array('imgSize'=>22)) . ' ' . $row['username'] .'</strong></a> (' . $row['counted'] . ' items)</span></li>';
        }
        return $html;
    }

    public function getEditorRank($user,$db) {
        $stmt = $this->_getEditorsByVolumeQuery($db);
        $i = 0;
        while ($row = $stmt->fetch()) {
            $i++;
            if ($row['id'] == $user->id) {
                $rank = $i;
            }
        }
        
        return $rank;
    }
    

    public function getMostRecentEditors($db,$limit=10) {
        $formatter = get_view()->format();
        $html = "";
        $select = new Omeka_Db_Select($db);
        $select->from(array('u'=>'users'), array('u.username','u.email','u.id','max(er.time) as modtime'))
                ->joinLeft(array('e'=>'entities'), "e.user_id = u.id", array('max(time)'))
                ->joinLeft(array('er'=>'entities_relations'), "er.entity_id = e.id",array())
                ->joinLeft(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
            ->where("time <> '0000-00-00 00:00:00'")
            ->group('u.username')
            ->order('modtime DESC')
            ->limit($limit);
        $stmt = $select->query();
        while ($row = $stmt->fetch()) {
            $html .= '<li><span class="text-info community-user-link"><strong><a href="/participate/profile/id/'. $row['id']. '">' . get_view()->gravatar($row['email'],array('imgSize'=>22)) . ' ' . $row['username'] .'</a></strong>: ' . $formatter->time_passed(strtotime($row['modtime'])) . '</span></li>';
        }

        return $html;

    }
    
    /* PRIVATE FUNCTIONS */
    
    private function _getEditorsByVolumeQuery($db,$limit=null){
        $select = new Omeka_Db_Select($db);
        $select->from(array('u'=>'users'), array('u.username', 'count(u.username) as counted','u.email','u.id'))
                ->joinInner(array('e'=>'entities'), "e.user_id = u.id", array())
                ->joinInner(array('er'=>'entities_relations'), "er.entity_id = e.id", array())
                ->joinInner(array('ers'=>'entity_relationships'), "er.relationship_id = ers.id",array())
                ->where("u.username != 'sschlitz'")
                ->group('u.username')
                ->order('count(u.username) DESC')
                ->limit($limit);

        $stmt = $select->query();
        return $stmt;
    }


 
}

?>
