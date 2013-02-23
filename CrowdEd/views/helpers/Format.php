<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of Format
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_View_Helper_Format extends Zend_View_Helper_Abstract {
    public function Format() {
        return $this;
    }
    
    
    public function time_passed($timestamp) {
        // this function gotten from here: http://www.devnetwork.net/viewtopic.php?f=50&t=113253
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
