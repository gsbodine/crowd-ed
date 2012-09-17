<?php 
    set_current_item($item);
    head();
    // require_once 'plugins/Zoomit/ZoomitPlugin.php';
?>
<div class="row">
    <div class="span6">
        <div class="site-title" style="text-align:center"><h1>Participate <small> Help us curate this item!</small></h1></div>
        <hr />
        <?php echo flash(); ?>
        <p class="lead">Item Identification #: <?php echo item('Dublin Core','Identifier'); ?></p>
        <form method="post" enctype="multipart/form-data" id="crowded-item-form" action="" class="form-horizontal">    
            <?php 
                $this->addHelperPath(CROWDED_DIR . '/helpers', 'CrowdEd_View_Helper');
                $formDisplay = crowded_display_element_sets_array_form($item, array('Item Type Metadata','Dublin Core','Crowdsourcing Metadata','Tags'));
                echo $formDisplay;
            ?>
            
            <hr />
            
            <div><i class="icon-tags"></i> <strong>Current Tags</strong></div>
            <div class="tags well well-small">
                <?php echo item_tags_as_string(); ?>
            </div>
            <label><strong>Add Tags:</strong></label>
            <div class="input-prepend"><span class="add-on"><i class="icon-tag"></i></span><?php 
                $tagList = tag_string(get_tags(),$link=false,$delimiter=",");
                $quotedTags = str_replace(",", "\",\"", $tagList);
                echo text(array(
                    'name' => 'tags',
                    'size' => '40',
                    'id' => 'tag-search',
                    'class'=>'span2',
                    'placeholder'=>'Add a tag...',
                    'data-provide'=>'typeahead',
                    'data-source'=>'["'.$quotedTags.'"]',
                    'data-items'=>'12',
                    'data-minLength' => '2',
                    ),
                @$_REQUEST['tags']); 
                ?></div>
            <hr />
            <div class="buttonbar">
                <?php echo submit(array('name'=>'submit','id'=>'save-changes','class'=>'submit btn btn-inverse','style'=>'text-align:center'),__('Save Changes')); ?>
            </div>
        </form>
    </div>
    <div class="span6">
        <?php 
            echo display_files_for_item(
            array('imageSize'=>'fullsize'),
            array('class'=>'image','style'=>'text-align:center'), 
            null);
        ?>
    </div>
</div>
<?php foot(); ?>
