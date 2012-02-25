<?php 
    set_current_item($item);
    head(); 
?>
<div class="sixteen columns">
    <div class="one-third column alpha">

        
        <div>    
            <?php echo item_thumbnail(); ?>
        </div>
        <hr />
        <?php // TODO: Check for additional items, then show if it has them -- otherwise, suppress the following section. ?>
        <p>Additional files for this item:</p>
        <div>
            <?php 
                echo display_files_for_item(
                array(),//options
                array('class'=>'image'), //wrapperAttributes
                null);
            ?>
        </div>
    </div>
    <div class="two-thirds column omega">
        <h2 style="text-align: center;">Participate</h2>
        <h3>Help us curate the Martha Berry Digital Archive</h3>
        <hr />
            
       <?php 
           $mixin = new ActsAsElementText($item);
           echo display_crowded_form_input_for_element($mixin->getElementByNameAndSetName('Description','Dublin Core'), $item); 
           echo display_crowded_form_input_for_element($mixin->getElementByNameAndSetName('Date', 'Dublin Core'), $item);
       ?>
        
    </div>
</div>
<?php foot(); ?>
