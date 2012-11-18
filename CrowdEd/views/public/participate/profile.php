<?php head(); ?>
<div class="row">
    <div class="span12">
        <h1><i class="icon-user"></i> <?php echo $user->username; ?> <small>My Editor Profile</small></h1>
        <hr />
    </div>
</div>
<div class="row">
    <div class="span6">
        <div class="well">
            <h3><i class="icon-heart-empty"></i> Favorite Items</h3>
            <?php echo featureUnavailable(); ?>
        </div>
    </div>
    <div class="span6">
        <div class="well">
            <h3><i class="icon-edit"></i> Items Recently Edited</h3>
            <?php 
                //echo displayLastItemsEditedByUser($user->entity_id,5); 
                echo featureUnavailable();
            ?>
            
        </div>
    </div>
</div> 
<?php foot(); ?>
