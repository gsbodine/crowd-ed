<?php
   
    function crowded_display_form_element($element, $record, $options = array()) {
        $html = '';
        if (is_array($element)) {
                foreach ($element as $key => $e) {
                    $html .= __v()->elementForm($e, $record, $options);
                }
            } else {
                $html = __v()->elementForm($element, $record, $options);
            }
	return $html;
    }
    
    function isCrowdEdElement($element) {
        // TODO: Change this from hardcoded to a linked table ala SimpleVocab or similar
        $crowdedElements = array('Title','Description','Creator','Recipient','Date','Type','Flag for Review','Script Type');
        if (in_array($element->name, $crowdedElements)) {
            return true;
        }
    }

?>
