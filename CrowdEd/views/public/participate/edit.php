<?php 
    set_current_record('item', $item);
    echo head();
    $elements = $item->getAllElements();
?>
<div id="helpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="modalLabel"><i class="icon-question-sign"></i> Help and Editing Guidelines</h3>
    </div>
    <div class="modal-body">
        <h4 class="lead"><i class="icon-info-sign"></i> Instructions</h4>
        <ol>
            <li>Complete the fields using the document contents to guide your responses.</li>
            <li>Click on the document to increase its size and make the text easier to decipher. </li>
            <li>Accuracy is important. Please do your best to provide precise and accurate descriptions. </li>
            <li>Try to complete all fields. If the document does not contain enough information for you to do so, edit as much as you can and leave fields blank if necessary.</li>
            <li>When you've finished editing, review your responses for accuracy. Then click <span class="btn btn-primary btn-mini">Save Changes</span>. </li>
            <li>If the document is too difficult to read, click <span class="text-warning"><i class="icon-remove-sign"></i>Cancel and return to item</span> and proceed to the next item to edit. </li>
        </ol>
        <hr />
        
        <h4 class="lead"><i class="icon-question-sign"></i> What if...</h4>
        <dl>
            <dt>I can’t read the document:</dt>
            <dd>Take your time and try to decipher the text. If you can’t, simply scroll down and click <span class="text-warning"><i class="icon-remove-sign"></i> Cancel and return to item</span> to edit a different document. </dd>
        </dl>
        <dl>
            <dt>The document contains more than one date:</dt>
            <dd>Use the date written by the document’s main author. Some documents contain a typed date in addition to the date written by the author. We’re interested in the date written by the document’s main author. </dd>
        </dl>
        <dl>
            <dt>The document contains more than one author or recipient:</dt>
            <dd>Click on XXX to add additional author and/or recipient fields. </dd>
        </dl>
        <dl>
            <dt>A title or name is typed or written in by someone other than the author: </dt>
            <dd>Use the name written by the author. Some documents contain a title or name in addition to that used by the author. We’re interested in the name as written by the document’s main author. </dd>
        </dl>
        <dl>
            <dt>The author signed the letter and typed his/her name but the names are not identical:</dt>
            <dd>Some authors sign a letter by hand (e.g. Margaret Dunham) above the formal name and title typed in the closing (e.g. Mrs. Carroll Dunham). Use the signature. If you can’t read the signature, then using the formal name is an acceptable alternative.</dd>
        </dl>
        <dl>
            <dt>One or more of the fields contains editing errors, graffiti, or inappropriate language:</dt>
            <dd>Please help us correct these by editing the document immediately. If you're not sure how to correct the problem, flag it for review and we'll be sure to review and correct the problem ASAP. </dd>
        </dl>
        <dl>
            <dt>I don’t know what a <span class="label label-inverse"><i class="icon-tag"></i> Tag</span> is: </dt>
            <dd>A tag is a keyword or label that describes a document's subject. Tags can help readers and editors identify and sort similar documents. If you’re not sure what tag to use, feel free to leave the tags field blank.</dd>
        </dl>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>
<div class="row">
    <div class="span6">
        <p class="lead" style="text-align:center;">Item Identification #: <?php echo metadata($item,array('Dublin Core','Identifier')); ?></p>
        <?php 
            echo files_for_item(
            array('imageSize'=>'fullsize'),
            array('class'=>'image','style'=>'text-align:center'), 
            null);
        ?>
    </div>
    <div class="span6">
        <div style="text-align:center;"><?php echo link_to_item($text='<i class="icon-arrow-left"></i> return to item display page',$props=array(),$action='show',$item) ?></div>
        <div class="site-title">
            <h1>Participate <small> Help us catalog this item!</small><span class="pull-right"><small><a href="#helpModal" role="button" data-toggle="modal" class="text-warning"><i class="icon-question-sign"></i> Help</a></small></span></h1>
        </div>
        <hr />
        <form method="post" enctype="multipart/form-data" id="crowded-item-form" action="" autocomplete="off">    
        <?php echo flash(); ?>
        
        <div class="row">
        <?php  
            $itemType = element_form($elements['Dublin Core']['Type'], $item, $options=array('columnSpan'=>'3'));
            echo $itemType; 
        ?>
            <div class="btn-group">
        <?php 
            $scriptType = element_form($elements['Item Type Metadata']['Script Type'], $item, $options=array('columnSpan'=>'3'));
            echo $scriptType; 
        ?>  </div>
        </div>
        
        <hr />
        <div class="row">
        <?php  $itemDate = element_form($elements['Dublin Core']['Date'], $item);
               echo $itemDate; 
        ?>
        </div>
            
        <hr />
        
        <div class="row">
        <?php  $itemTitle = element_form($elements['Dublin Core']['Title'], $item, $options=array('fieldColumnSpan'=>'6'));
               echo $itemTitle; 
        ?>
        </div>
            
        <hr />
        
        <div class="row">
        <?php  $itemDescription = element_form($elements['Dublin Core']['Description'], $item, $options=array('fieldColumnSpan'=>'6'));
               echo $itemDescription; 
        ?>
        </div>
            
        <hr />
        
        <div class="row">
        <?php $itemCreator = $this->personNameElementForm($elements['Dublin Core']['Creator'], $item, $options=array('columnSpan'=>'6'));
            echo $itemCreator;
        ?>
        </div>
        
        <hr />
        
        <div class="row">  
        <?php // todo: fix this to only do recipient for the right kind of document 
            $itemRecipient = $this->personNameElementForm($elements['Item Type Metadata']['Recipient'], $item, $options=array('columnSpan'=>'6'));
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
                        $tagList = tag_string($tags,$link=null,$delimiter=",");
                        $quotedTags = str_replace(",", "\",\"", $tagList);
                        echo get_view()->formText('tags', null, array(
                            'id' => 'search-tags',
                            'class'=>'span3',
                            'placeholder'=>'Add tags separated by commas',
                            'preventSubmitOnError'=>true,
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
                    <?php echo tag_string('item'); ?>
                </div>
            </div>
            
        </div>
        <?php if (plugin_is_active('Geolocation')): ?>
        <hr />
        <div class="row">
            <div class="span6">
                <?php 
                    $geo = new GeolocationPlugin();
                    echo $geo->displayMapForm($item);
                ?>
            </div>
        </div>
        <?php endif; ?>
        
        <hr />
        
        <div class="row">
            <?php //TODO: this should appear somewhere else, probably cuz even those without being logged in should be able to flag items?
                $itemFlag = element_form($elements['Crowdsourcing Metadata']['Flag for Review'], $item);
                echo $itemFlag;
            ?> 
        </div>
        <div class="row">
            <div class="span6" style="text-align:center">
                <hr />
                <?php echo get_view()->formSubmit('submit','Save Changes',array('id'=>'save-changes','class'=>'submit btn btn-primary pull-left')); ?>
                <?php echo link_to_item($text='<i class="icon-remove-sign"></i> Cancel and return to item',$props=array('class'=>'text-warning pull-right'),$action='show',$item) ?>
            
            </div>
        </div>
        </form>
    </div>
        
    </div>
    
<?php echo foot(); ?>
