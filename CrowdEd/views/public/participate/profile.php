<?php 
    echo head();
    $user = current_user();
    $entity = new Entity();
    $entity->getEntityByUser($user);

?>
<div class="row">
    <div class="span12">
        <h1><i class="icon-user"></i> <?php echo $user->username; ?> <small>My Editor Profile</small></h1>
        <h2><?php echo $entity->first_name . ' ' . $entity->last_name ?></h2>
        
        <hr />
    </div>
</div>
<div class="row">
    <div class="span6">
        <div class="well">
            <h3><i class="icon-heart-empty"></i> Favorite Items</h3>
            <?php echo $this->profile($user,$entity)->featureUnavailable(); ?>
        </div>
    </div>
    <div class="span6">
        <div class="well">
            <h3><i class="icon-edit"></i> Items Recently Edited</h3>
            <?php 
                //echo displayLastItemsEditedByUser($user->entity_id,5); 
                echo $this->profile($user,$entity)->featureUnavailable();
            ?>
            
        </div>
    </div>
</div> 
<?php echo foot(); ?>
