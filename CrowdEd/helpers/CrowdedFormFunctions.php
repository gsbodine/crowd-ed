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

    function appendZoomIt($item)
    {
        // Get valid images.
        $images = array();
        foreach (__v()->item->Files as $file) {
            $extension = pathinfo($file->archive_filename, PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $this->_supportedExtensions)) {
                continue;
            }
            $images[] = $file;
        }
        if (empty($images)) {
            return;
        }
?>
<script type="text/javascript">
jQuery(document).ready(function () {
    var imageviewer = jQuery('#zoomit_imageviewer');
    jQuery('.zoomit_images').click(function(event) {
        event.preventDefault();
        imageviewer.empty();
        imageviewer.append(
        '<h2>Viewing: ' + jQuery(this).text() + '</h2>' 
      + '<iframe src="' + this.href + '" ' 
      + 'width="<?php echo is_admin_theme() ? get_option('zoomit_width_admin') : get_option('zoomit_width_public'); ?>" ' 
      + 'height="<?php echo is_admin_theme() ? get_option('zoomit_height_admin') : get_option('zoomit_height_public'); ?>" ' 
      + 'style="border: none;"></iframe>');
    });
});
</script>
<div>
    <p>Click below to view an image using the <a href="http://zoom.it/">Zoom.it</a> viewer.</p>
    <ul>
        <?php foreach($images as $image): ?>
        <li><a href="<?php echo html_escape(__v()->url('zoomit/index/index/file-id/' . $image->id)); ?>" class="zoomit_images"><?php echo html_escape($image->original_filename); ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
<div id="zoomit_imageviewer"></div>
<?php
    
}

?>
