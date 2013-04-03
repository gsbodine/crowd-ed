<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of Maps
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_View_Helper_Maps extends Zend_View_Helper_Abstract  {
    public function maps() {
        return $this;
    }
    
    function maps_search_form($props = array(), $formActionUri = null) {
        return get_view()->partial(
            'items/map-search-form.php', 
            array('formAttributes' => $props, 'formActionUri' => $formActionUri)
        );
    }
    
}

?>
