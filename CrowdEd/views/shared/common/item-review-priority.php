<div class="two-thirds column omega"> 
    <fieldset><legend>Review Information</legend>
    <div class="one-third column alpha">
        <label for="crowded_item_review_priority"><h5>Review Priority for This Item</h5></label>
            <div>How important do you feel it is for archive staff to review this item?</div>
            <?php echo __v()->formRadio('crowded_item_review_priority', 
                            $crowdedReviewPriority, 
                            null, 
                            array('low' => 'Low Priority', 
                                  'important' => 'Moderate Priority', 
                                  'critical' => 'High Priority'), 
                            null); ?>
    </div>
    <?php // TODO: the following should only appear for >= 'Moderate Priority' selections above ?>
    <div class="field one-third column omega">
        <label for="crowded_item_review_reason"><h5>Reason for Your Priority Selection</h5></label>
        <?php echo __v()->formTextarea('crowded_item_review_reason', $item->priority, array('rows'=>'6', 'cols'=>'45')); ?>
    </div>
        <!-- <input type="submit" id="crowded_submit_review_priority" value="Submit Review Priority"/>
        <input type="hidden" name="item_id" value="<?php print $item->id;?>"/> -->
    </fieldset>
</div>
