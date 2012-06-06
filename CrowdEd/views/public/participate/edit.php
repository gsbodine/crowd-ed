<?php 
    set_current_item($item);
    head();
    
    require_once 'plugins/Zoomit/ZoomitPlugin.php';
    
?>
<div class="sixteen columns">
    <div class="four columns alpha">
        <!-- <div>    
            <?php 
                echo item_thumbnail(); 
               // TODO: Implement ZoomIt plugin hook here...
               $zoomer = new ZoomitPlugin;
               //$zoomer.setUp();
               //$zoomer.append(item_thumbnail);
            ?>
        </div> 
        <hr /> -->
        <div>
            <?php 
                echo display_files_for_item(
                array('imageSize'=>'thumbnail'), //options
                array('class'=>'image'), 
                null);
            ?>
        </div>
    </div>
    <div class="twelve columns omega">
        <h2 style="text-align: center;">Participate</h2>
        <!-- <h3>Help us curate the Martha Berry Digital Archive</h3> -->
        <hr />
        <?php echo flash(); ?>
        <p><strong>Current Item Identification #: <?php echo item('Dublin Core','Identifier'); ?></strong></p>
        <form method="post" enctype="multipart/form-data" id="item-form" action="">
            
            <?php 
                $this->addHelperPath(CROWDED_DIR . '/helpers', 'CrowdEd_View_Helper');
                $formDisplay = crowded_display_element_sets_array_form($item, array('Item Type Metadata','Dublin Core','Crowdsourcing Metadata'));
                echo $formDisplay;
            ?>
            <div class="twelve columns">
                <?php echo submit(array('name'=>'submit','id'=>'save-changes','class'=>'submit'),__('Save Changes')); ?>
            </div>
        </form>
        
    </div>
</div>
<?php foot(); ?>
