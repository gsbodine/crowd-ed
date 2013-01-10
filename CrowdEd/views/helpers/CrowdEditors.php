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
        $select->from(array('u'=>'users'), array('u.username', 'count(u.username)'))
                ->joinInner(array('e'=>'entities'), "e.user_id = u.id", array())
                ->joinInner(array('er'=>'entities_relations'), "er.entity_id = e.id", array())
                ->joinInner(array('ers'=>'entity_relationships'), "er.relationship_id = ers.id",array())
                ->group('u.username')
                ->order('count(u.username) DESC')
                ->limit($limit);

        $stmt = $select->query();
        while ($row = $stmt->fetch()) {
            $html .= '<li><span class="label label-inverse"><i class="icon-user"></i> '. $row['username'] .'</span></li>';
        }
        return $html;
    }

    public function getMostRecentEditors($db,$limit=10) {
        $html = "";
        $select = new Omeka_Db_Select($db);
        $select->from(array('u'=>'users'), array('u.username'))
                ->joinInner(array('e'=>'entities'), "e.User_id = u.id", array('max(time)'))
                ->joinInner(array('er'=>'entities_relations'), "er.entity_id = e.id",array())
                ->joinInner(array('ers'=>'entity_relationships'), "ers.id = er.relationship_id", array())
            ->where("time <> '0000-00-00 00:00:00'")
            ->group('u.username')
            ->order('max(time) DESC')
            ->limit($limit);
        $stmt = $select->query();
        while ($row = $stmt->fetch()) {
            $html .= '<li><span class="label"><i class="icon-user"></i> '. $row['username'] .'</span></li>';
        }

        return $html;

    }

}

?>
