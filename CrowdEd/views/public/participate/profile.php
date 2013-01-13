<?php 
    echo head();
    $user = current_user();
    $entity = new Entity();
    $entity->getEntityByUserId($user->id);

?>
<div class="row">
    <div class="span12">
        <h1><i class="icon-user"></i> <?php echo $user->username; ?> <small>My Editor Profile</small></h1>
        <p class="lead"><strong><?php echo $user->name; ?> (<?php echo $user->email; ?>) <a href=""><i class="icon-edit"></i> Edit</a></strong></p>
        <hr />
    </div>
</div>
<div class="row">
    <div class="span8">
        <h3>You're an editor.</h3>
        <p class="lead"><a href="/community"><i class="icon-eye-open"></i> See what others have contributed and where <em>you</em> could help most.</a> 
            If you you're not sure where to go from here, <a href="/participate">take a look at the <i class="icon-group"></i> Participate page</a>.</p>
    </div>
    <div class="span4">
      
    </div>
</div>
<div class="row">
    <div class="span4">
        <div class="well">
            <h3><i class="icon-heart-empty"></i> Favorite Items</h3>
            <ul class="unstyled">
                <?php echo $this->profile()->getUserFavorites(); ?>
            </ul>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h3><i class="icon-folder-open-alt"></i> Items Recently Edited</h3>
            <?php 
                $itemList = $this->profile()->getItemsEditedByUser($user,5);
                if ($itemList) : ?>
                <ul class="unstyled">
                    <?php echo $itemList; ?>
                </ul>
            <?php else: ?>
                <p class="alert alert-info">You haven't edited anything yet. <strong><a href="/getting-started">Why not get started?</a></strong></p>
            <?php endif; ?>
            
        </div>
    </div>
    <div class="span4">
        
    </div>
</div> 
<?php echo foot(); ?>
