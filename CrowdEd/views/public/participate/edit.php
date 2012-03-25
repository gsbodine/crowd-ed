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
        <p>Additional files for this item:</p>
        <div>
            <?php 
                echo display_files_for_item(
                array(), //options
                array('class'=>'image'), 
                null);
            ?>
        </div>
    </div>
    <div class="two-thirds column omega">
        <h2 style="text-align: center;">Participate</h2>
        <!-- <h3>Help us curate the Martha Berry Digital Archive</h3> -->
        <hr />
        <?php echo flash(); ?>
        <h4>Current Item: <?php echo item('Dublin Core','Title'); ?></h4>
        <form method="post" enctype="multipart/form-data" id="item-form" action="">
            
            <?php 
                $this->addHelperPath(CROWDED_DIR . '/helpers', 'CrowdEd_View_Helper');
                $formDisplay = crowded_display_element_set_form($item,'Dublin Core');
                //$formDisplay = display_element_set_form($item,'Dublin Core');
                echo $formDisplay;
            ?>
            <div>
                <?php echo submit(array('name'=>'submit', 'id'=>'save-changes', 'class'=>'submit'), __('Save Changes')); ?>
            </div>
        </form>
        
    </div>
</div>
<?php foot(); ?>
