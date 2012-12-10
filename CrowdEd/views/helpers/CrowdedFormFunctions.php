<?php
   
    function crowded_display_form_element($element, $record, $options = array()) {
        $html = '';
        __v()->addHelperPath(CROWDED_DIR . '/helpers','Crowded_View_Helper');
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
    
    function is_plugin_installed($name) {
        $plugin = Zend_Registry::get('pluginloader')->getPlugin($name);
        if ($plugin && $plugin->isInstalled()) {
            return true;
        }
    } 

?>
