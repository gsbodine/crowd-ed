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

    $html = '<div class="progress progress-striped progress-success active"><div class="bar" style="width: '; 
    $html .= $completionPercent;
    $html .= '%;"></div></div>';
    return $html;
        
}

?>
