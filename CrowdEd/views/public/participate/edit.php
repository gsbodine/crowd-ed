<?php 
    set_current_item($item);
    head();
    // require_once 'plugins/Zoomit/ZoomitPlugin.php';
?>
<div class="row">
    <div class="span12">
        
    </div>
</div>
<div class="row">
    <div class="span6">
        <p class="lead" style="text-align:center;">Item Identification #: <?php echo item('Dublin Core','Identifier'); ?></p>
        <?php 
            echo display_files_for_item(
            array('imageSize'=>'fullsize'),
            array('class'=>'image','style'=>'text-align:center'), 
            null);
        ?>
    </div>
    <div class="span6">
        <div class="site-title" style="text-align:center"><h1>Participate <small> Help us curate this item!</small></h1></div>
        <hr />
        <form method="post" enctype="multipart/form-data" id="crowded-item-form" action="">    
        <?php echo flash(); ?>
        <?php  $itemDate = crowded_display_form_element( $item->getElementByNameAndSetName('Date','Dublin Core'),$item,$columnSpan='6');
               echo $itemDate; 
        ?>
        <hr />
        <div><i class="icon-tags"></i> <strong>Current Tags</strong></div>
        <div class="tags well well-small">
            <?php echo item_tags_as_string(); ?>
        </div>
        <label for="tag-search"><strong>Add Tags:</strong></label>
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
            ?>
        </div>
        <div class="buttonbar" style="text-align:center;">
            <hr />
            <?php echo submit(array('name'=>'submit','id'=>'save-changes','class'=>'submit btn btn-primary','style'=>'text-align:center'),__('Save Changes')); ?>
        </div>
        </form>
    </div>
        
    </div>
    
</div>
    
</div>
<?php foot(); ?>
