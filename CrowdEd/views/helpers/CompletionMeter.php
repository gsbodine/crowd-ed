<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of CompletionMeter
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_View_Helper_CompletionMeter {
    public function completionMeter() {
        $totalItems = get_db()->getTable('Item')->count();
        //$undoneItems = count($this->_getUneditedItems());
        $potentiallyDoneItems = count($this->_getUnlockedEditedItems());
        $totallyDoneItems = count($this->_getLockedEditedItems());

        $partialCompletionPercent = number_format($potentiallyDoneItems / $totalItems * 100);
        $totalCompletionPercent = number_format($totallyDoneItems / $totalItems * 100);
        $uneditedPercent = number_format(100 - $partialCompletionPercent - $totalCompletionPercent);
   
        $html = '<div><small class="pull-left">'. $potentiallyDoneItems + $totallyDoneItems .' Edited Documents</small>';
        $html .= '<small class="pull-right">'. $totalItems .' Documents in Collection</small>';
        $html .= '<div class="progress progress-striped active">';
        $html .= '<div class="bar bar-success" style="width: '. $totalCompletionPercent .'%;"></div>';
        $html .= '<div class="bar bar-warning" style="width: ' . $partialCompletionPercent . '%;"></div>';
        $html .= '<div class="bar bar-danger" style="width: '. $uneditedPercent .'%;"></div>';
        $html .= '</div>';
        return $html;

    }
    
    private function _getLockedEditedItems() {
        // todo -- below is just a mock-up that returns a dummy array
        return array(1,2,1,2,1,2,1,2,1,2,1,2,1,2,1,2);
    }
    
    private function _getUnlockedEditedItems() {
        // todo
        return array(1,2,1,2,1,2,1,2,1,2,1,2,1,2,1,2);
    }
}

?>
