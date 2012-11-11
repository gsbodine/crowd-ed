<?php 
    set_current_item($item);
    head();
    
?>


<h1><?php echo $this->itemType .'<br />Item ID: '. $this->id ?></h1>
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
        <div style="text-align:center;"><?php echo link_to_item($text='<i class="icon-arrow-left"></i> return to item display page',$props=array(),$action='show',$item) ?></div>
        <div class="site-title" style="text-align:center">
            <h1>Participate <small> Help us catalog this item!</small></h1>
        </div>
        <hr />
        <form method="post" enctype="multipart/form-data" id="crowded-item-form" action="">    
        <?php echo flash(); ?>
        
        <div class="row">
        <?php $itemType = crowded_display_form_element($item->getElementByNameAndSetName('Type','Dublin Core'),$item,$options=array('columnSpan'=>'3'));
            echo $itemType;
        ?>
            <div class="btn-group">
        <?php 
            // FIXME: if ($item->getElementByNameAndSetName('ScriptType','Item Type Metadata')) {
                $scriptType = crowded_display_form_element($item->getElementByNameAndSetName('Script Type','Item Type Metadata'),$item,$options=array('columnSpan'=>'3'));
                echo $scriptType; 
            //}
        ?>  </div>
        </div>
        
        <hr />
        <div class="row">
        <?php  $itemDate = crowded_display_form_element( $item->getElementByNameAndSetName('Date','Dublin Core'),$item);
               echo $itemDate; 
        ?>
        </div>
            
        <hr />
        
        <div class="row">
        <?php  $itemTitle = crowded_display_form_element($item->getElementByNameAndSetName('Title','Dublin Core'),$item,$options=array('fieldColumnSpan'=>'6'));
               echo $itemTitle; 
        ?>
        </div>
            
        <hr />
        
        <div class="row">
        <?php  $itemDescription = crowded_display_form_element($item->getElementByNameAndSetName('Description','Dublin Core'),$item,$options=array('fieldColumnSpan'=>'6'));
               echo $itemDescription; 
        ?>
        </div>
            
        <hr />
        
        <div class="row">
        <?php $itemCreator = crowded_display_form_element($item->getElementByNameAndSetName('Creator','Dublin Core'),$item,$options=array('columnSpan'=>'6'));
            echo $itemCreator;
        ?>
        </div>
        
        <hr />
        
        <div class="row">  
        <?php // todo: fix this to only do recipient for the right kind of document 
            $itemRecipient = crowded_display_form_element($item->getElementByNameAndSetName('Recipient','Item Type Metadata'),$item,$options=array('columnSpan'=>'6'));
            echo $itemRecipient;
        ?>
        </div> 
        
        <hr />
        
        <div class="row">
            <div class="span3">
                <div class="row">
                    <div><label for="tag-search" class="span3"><i class="icon-tags"></i> Add Tags</label></div>  
                </div>
                <div class="row">
                    <div class="add-tags span3"><?php 
                        $tagList = tag_string(get_tags(),$link=false,$delimiter=",");
                        $quotedTags = str_replace(",", "\",\"", $tagList);
                        echo textarea(array(
                            'name' => 'tags',
                            'id' => 'search-tags',
                            'class'=>'span3',
                            'rows'=>'2',
                            'placeholder'=>'Add tags, separated by commas...',
                            'data-provide'=>'typeahead',
                            'data-source'=>'["'.$quotedTags.'"]',
                            'data-items'=>'12',
                            'data-minLength' => '2',
                            ),
                        @$_REQUEST['tags']); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="span3">
                <div class="tags well well-small">
                    <div><i class="icon-tags"></i> Current Tags</div>
                    <?php echo item_tags_as_string(); ?>
                </div>
            </div>
            
        </div>
        <hr />
        
        <div class="row">
            <?php //TODO: this should appear somewhere else, probably cuz even those without being logged in should be able to flag items?
                $itemFlag = crowded_display_form_element($item->getElementByNameAndSetName('Flag for Review','Crowdsourcing Metadata'),$item);
                echo $itemFlag;
            ?> 
        </div>
        <div class="row">
            <div class="span6" style="text-align:center">
                <hr />
                <?php echo submit(array('name'=>'submit','id'=>'save-changes','class'=>'submit btn btn-primary pull-left'),__('Save Changes')); ?>
                <?php echo link_to_item($text='<i class="icon-remove-sign"></i> Cancel and return to item',$props=array('class'=>'text-warning pull-right'),$action='show',$item) ?>
            
            </div>
        </div>
        </form>
    </div>
        
    </div>
    
<?php foot(); ?>
