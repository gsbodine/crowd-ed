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
        $potentiallyDoneItems = $this->_getNumUnlockedEditedItems();
        $totallyDoneItems = $this->_getNumLockedEditedItems();

        $partialCompletionPercent = number_format($potentiallyDoneItems / $totalItems * 100);
        $totalCompletionPercent = number_format($totallyDoneItems / $totalItems * 100);
        $uneditedPercent = number_format(100 - $partialCompletionPercent - $totalCompletionPercent);
?>
<div class="row">
        <div class="span2">
                <p>Finalized Documents</p>
                <div class="progress progress-striped span1">
                    <div class="bar bar-success" style="width:100%;" ><strong><?php echo $totallyDoneItems; ?></strong></div> 
                </div>
        </div>
        <div class="span2">
                <p>Documents Under Review</p>
                <div class="progress progress-striped span1">
                    <div class="bar bar-warning" style="width:100%;" ><strong><?php echo $potentiallyDoneItems; ?></strong></div>
                </div>
        </div>
        <div class="span2">
                <p>Unedited Documents</p>
                <div class="progress progress-striped span1">
                    <div class="bar bar-danger" style="width:100%;" ><strong><?php echo $totalItems - $potentiallyDoneItems - $totallyDoneItems; ?></strong></div>
                </div>
        </div>
</div>
        <?php
        $html = '<div class="row"><div class="span6">';
        $html .= '<p class="lead">Total Percentage Completion</p>';
        //$html .= '<span class="pull-left">'. $potentiallyDoneItems + $totallyDoneItems .' Edited Documents</span>';
        //$html .= '<span class="pull-right">'. $totalItems .' Total Documents</span>';
        $html .= '<div class="progress progress-striped active">';
        $html .= '<div class="bar bar-success" style="width: '. $totalCompletionPercent .'%;"></div>';
        $html .= '<div class="bar bar-warning" style="width: ' . $partialCompletionPercent . '%;"></div>';
        $html .= '<div class="bar bar-danger" style="width: '. $uneditedPercent .'%;"></div>';
        $html .= '</div></div></div>';
        return $html;

    }
    
    private function _getNumLockedEditedItems() {
        $select = new Omeka_Db_Select(get_view()->_db);
        $select->from(array('esi'=>'edit_statuses_items'), array('num'=>'COUNT(*)'))
                ->joinInner(array('es'=>'edit_statuses'), "es.id = esi.edit_status_id", array())
                ->where('es.isLockedStatus = 1');

        $stmt = $select->query();
        $countLockedItems = $stmt->fetch();
        $itemsNum = $countLockedItems['num'];
        return $itemsNum;
    }
    
    private function _getNumUnlockedEditedItems() {
        $select = new Omeka_Db_Select(get_view()->_db);
        $select->from(array('esi'=>'edit_statuses_items'), array('num'=>'COUNT(*)'))
                ->joinInner(array('es'=>'edit_statuses'), "es.id = esi.edit_status_id", array())
                ->where('es.isLockedStatus = 0');

        $stmt = $select->query();
        $countLockedItems = $stmt->fetch();
        $itemsNum = $countLockedItems['num'];
        return $itemsNum;
    }
}

?>
