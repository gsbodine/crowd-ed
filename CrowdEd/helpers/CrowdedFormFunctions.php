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
    
    function crowded_display_element_sets_array_form($record, $arrayElementSetNames) {
        
        foreach ($arrayElementSetNames as $key => $elementSetName) {
            $elementsInSetsArray[] = get_db()->getTable('Element')->findBySet($elementSetName);
        } 
        
        foreach ($elementsInSetsArray as $key => $elementSet) {
            foreach ($elementSet as $key => $element) {
                $elements[] = $element;
            }  
        }
        $sortedElements = customArraySort($elements,'order');
        foreach ($sortedElements as $key => $element) { 
            $cols = crowded_element_columns_width($element);
            $html .= crowded_display_form_input_for_element($element, $record,array('columnNum'=>$cols));
        }    
        return $html;
    }
    
    function crowded_display_element_set_form($record, $elementSetName) {
        $elements = get_db()->getTable('Element')->findBySet($elementSetName);
        $html = '';
        $sortedElements = customArraySort($elements,'order');
        foreach ($sortedElements as $key => $element) {
            $html .= crowded_display_form_input_for_element($element, $record);
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
    
    function customArraySort($array, $index, $order='asc', $natsort=TRUE, $case_sensitive=FALSE) {
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
    
    function crowded_element_columns_width($element) {
        switch ($element['name']) {
        case "Script Type":
        case "Type":
        case "Creator":
        case "Recipient":
            $cols = "five";
            break;
        default : 
            $cols = "twelve";
            break;
        }
        
        return $cols;
    }

?>    
