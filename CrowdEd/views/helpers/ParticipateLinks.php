<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of ParticipateLinks
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_View_Helper_ParticipateLinks extends Zend_View_Helper_Abstract {
    public function participateLinks() {
        return $this;
    }
    
    public function createParticipateLink(Item $item, $linkText,$icon=null) {
        $esi = new EditStatusItems();
        $status = $esi->getItemEditStatus($item);
        if ($status) {
            $es = new EditStatus();
            $lockStatus = $es->getLockedStatus($status->edit_status_id);
        } else {
            $lockStatus = 0;
        }
        
        $html = '';
        
        if ($lockStatus == 0) {
            if (!$icon) {
                $linkIcon = '';
            } else {
                $linkIcon = '<i class="'. $icon .'"></i> ';
            }
            $html = '<a href="/participate/edit/'. $item->id .'" class="btn btn-success" style="margin-left: 2em;">'. $linkIcon . $linkText .'</a>';
        } 
        echo $html;
    }
}

?>
