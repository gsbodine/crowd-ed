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
    
    /* function customArraySort($array, $index, $order='asc', $natsort=TRUE, $case_sensitive=FALSE) {
        // most of this came from the PHP documentation's comments
        if(is_array($array) && count($array)>0) {
            foreach(array_keys($array) as $key)
            $temp[$key]=$array[$key][$index];
            if(!$natsort) {
                if ($order=='asc')
                    asort($temp);
                else   
                    arsort($temp);
            }
            else
            {
                if ($case_sensitive===true)
                    natsort($temp);
                else
                    natcasesort($temp);
            if($order!='asc')
                $temp=array_reverse($temp,TRUE);
            }
            foreach(array_keys($temp) as $key)
                if (is_numeric($key))
                    $sorted[]=$array[$key];
                else   
                    $sorted[$key]=$array[$key];
            return $sorted;
        }
        return $sorted;
    }
     * 
     */
    /*
    function crowded_item_tags($item) {
        $tagArray = get_tags($item);
        $tagJson = '[';
        foreach ($tagArray as $tag) {
            $tagJson .= '"'.$tag.'",';
        }
        $tagJson .= ']';
        return $tagJson;
    }*/
    

?>
