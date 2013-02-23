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
    
    public function getEditorsByVolume($db,$limit=10){
        $html = "";
        $select = new Omeka_Db_Select($db);
        $select->from(array('u'=>'users'), array('u.username', 'count(u.username) as counted','u.email','u.id'))
                ->joinInner(array('e'=>'entities'), "e.user_id = u.id", array())
                ->joinInner(array('er'=>'entities_relations'), "er.entity_id = e.id", array())
                ->joinInner(array('ers'=>'entity_relationships'), "er.relationship_id = ers.id",array())
                ->group('u.username')
                ->order('count(u.username) DESC')
                ->limit($limit);

        $stmt = $select->query();
        while ($row = $stmt->fetch()) {
            $html .= '<li><span class="text-info community-user-link"><strong><a href="/participate/profile/'. $row['id']. '">' . get_view()->gravatar($row['email'],array('imgSize'=>22)) . ' ' . $row['username'] .'</strong></a> (' . $row['counted'] . ' items)</span></li>';
        }
        return $html;
    }

    public function getMostRecentEditors($db,$limit=10) {
        $html = "";
        $select = new Omeka_Db_Select($db);
        $select->from(array('u'=>'users'), array('u.username','u.email','u.id','er.time as modtime'))
                ->joinInner(array('e'=>'entities'), "e.user_id = u.id", array('max(time)'))
                ->joinInner(array('er'=>'entities_relations'), "er.entity_id = e.id",array())
                ->joinInner(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
            ->where("time <> '0000-00-00 00:00:00'")
            ->group('u.username')
            ->order('max(time) DESC')
            ->limit($limit);
        $stmt = $select->query();
        while ($row = $stmt->fetch()) {
            $html .= '<li><span class="text-info community-user-link"><strong><a href="/participate/profile/'. $row['id']. '">' . get_view()->gravatar($row['email'],array('imgSize'=>22)) . ' ' . $row['username'] .'</a></strong>: ' . $this->_time_passed(time($row['modtime'])) . '</span></li>';
        }

        return $html;

    }


    private function _time_passed($timestamp) {
        $diff = time() - (int)$timestamp;

        if ($diff == 0) 
             return 'just now';

        $intervals = array
        (
            1                   => array('year',    31556926),
            $diff < 31556926    => array('month',   2628000),
            $diff < 2629744     => array('week',    604800),
            $diff < 604800      => array('day',     86400),
            $diff < 86400       => array('hour',    3600),
            $diff < 3600        => array('minute',  60),
            $diff < 60          => array('second',  1)
        );

         $value = floor($diff/$intervals[1][1]);
         return $value.' '.$intervals[1][0].($value > 1 ? 's' : '').' ago';
    }
 
}

?>
