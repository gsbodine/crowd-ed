<?php
    

    function display_crowded_form_input_for_element($element, $record, $options = array()) {
    
        $html = '';

        if (is_array($element)) {
            foreach ($element as $key => $e) {
                $html .= __v()->crowdedElementForm($e, $record, $options);
            }
        } else {
            $html = __v()->crowdedElementForm($element, $record, $options);
        }
	return $html;
    }
