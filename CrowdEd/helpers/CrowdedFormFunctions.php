<?php
   
    function crowded_display_form_input_for_element($element, $record, $options = array()) {
        $html = '';
        if (isCrowdEdElement($element)) {
            if (is_array($element)) {
                foreach ($element as $key => $e) {
                    $html .= __v()->itemForm($e, $record, $options);
                }
            } else {
                $html = __v()->itemForm($element, $record, $options);
            }
        }
	return $html;
    }
    
    function crowded_display_element_set_form($record, $elementSetName) {
        $elements = get_db()->getTable('Element')->findBySet($elementSetName);
        $html = '';
        foreach ($elements as $key => $element) {
            $html .= crowded_display_form_input_for_element($element, $record);
        }
        return $html;
    }
    
    function isCrowdEdElement($element) {
        $crowdedElements = array('Title','Description','Date');
        if (in_array($element->name, $crowdedElements)) {
            return true;
        }
    }
    
?>    
