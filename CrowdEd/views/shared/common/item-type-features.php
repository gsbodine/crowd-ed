<!-- <div id="crowded_item_type" class="four columns"> -->
        <!-- <form 
            action="" 
            id="crowded_item_type_features_form" 
            method="post" 
            accept-charset="utf-8"> -->   
       <div class="two-thirds column omega">
            <fieldset><legend><h4>Document Type Features</h4></legend>
            <div class="crowded-form-controls">
                    <label for="crowded_item_type">What kind of document is this?</label>
                    <?php echo __v()->formRadio('crowded_item_type', 
                                    $crowdedItemType, 
                                    array('class' => 'inlineRadio'), 
                                    array('type1' => 'Type One', 
                                          'type2' => 'Type Two', 
                                          'type3' => 'Type Three')
                                    ); 
                    ?>
                    <label for="crowded_item_document_date">When was the document composed?</label>
                    <?php 
                        echo __v()->formSelect('item_month','',null,array(''=>'','01'=>'Jan','02'=>'Feb','etc.'=>'etc.'));
                        echo __v()->formSelect('item_day','',null,array(''=>'','1'=>'1')); 
                        echo __v()->formSelect('item_year','',null,array(''=>'','1900'=>'1900'));
                    ?>
            </div>
            <div class="crowded-form-controls">
                <label for="crowded_item_author">Who composed this document?</label>
                <?php echo __v()->formText('crowded_item_author','',null); ?>
                
                <?php // TODO: If correspondence, show the following block, otherwise hide it ?>
                    <label for="crowded_item_recipient">To whom was this document written?</label>
                    <?php echo __v()->formText('crowded_item_recipient','Martha Berry',null); ?>
                </div>
                
                <!-- <input type="submit" id="crowded_submit_type_features" value="Submit Item Type Selection"/>
                <input type="hidden" name="item_id" value="<?php print $item->id;?>"/> -->
            
            </div>
            </fieldset>
       </div>
        <!-- </form>
</div> -->
