<?php 
    echo head();
    
    if (!$user) {
        $user = current_user();
    }
    $entity = new Entity();
    $entity = $entity->getEntityFromUser($user);
?>
<div class="row">
    <div class="span12">
        <h1><?php echo $this->gravatar($user->email,array('imgSize'=>60)) . ' ' . $user->username; ?> <small>Editor Profile</small></h1>
        <p class="lead"><strong>
        <?php 
            echo $user->name;
            if (trim($entity->institution) != '') {
                echo ' of ' . $entity->institution;
            }
            if ($user->id == current_user()->id) {
                echo ' <span class="pull-right"><a href="'. url(array('action'=>'update-account', 'controller'=>'user'), 'default') .'"><i class="icon-edit"></i> Edit Account Information</a></span></strong></p>';
            }
        ?>
        <hr />
    </div>
</div>

<div class="row">
    <div class="span4">
        <div class="well">
            <h3><i class="icon-heart-empty"></i> Favorite Items</h3>
            <?php 
            $favList = $this->profile()->getItemsEditedByUser($user,10);
            if ($favList) : ?>
                <ul class="unstyled">
                    <?php echo $this->profile()->getUserFavorites($user,10); ?>
                </ul>
                <hr />
                <p class="text-center text-error"><strong><a href="/participate/favorites/user/<?php echo $user->id ?>">
                   <?php if ($user == current_user()) {
                       echo '<i class="icon-list"></i> List all of your favorite items';
                   } else {
                       echo '<i class="icon-list"></i> List all the items favorited by ' . $user->username;
                   } ?>
                    </a></strong>
                </p>
             <?php else: ?>
                <p class="text-error"><strong><i class="icon-minus-sign"></i> No items have been marked as favorites by
                    <?php if ($user == current_user()) {
                        echo 'you. <a href="/items/browse">Why not go find some?</a>';
                    } else {
                        echo $user->username;
                    } ?>.
                    </strong></p>
             <?php endif ?>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h3><i class="icon-folder-open-alt"></i> Items Recently Edited</h3>
            <?php 
                $itemList = $this->profile()->getItemsEditedByUser($user,10);
                if ($itemList) : ?>
                <ul class="unstyled">
                    <?php echo $itemList; ?>
                </ul>
                <hr />
                <p class="text-center"><strong><a href="/participate/edited/user/<?php echo $user->id ?>"><i class="icon-list"></i> 
                    <?php if ($user == current_user()) {
                        echo ' List all of your edited items';
                    } else {
                        echo ' List all of the items favorited by ' . $user->username;
                    } ?>
                     </a></strong>
                 </p>
            <?php else: ?>
                <p class="alert alert-info">You haven't edited anything yet. <strong><a href="/getting-started">Why not get started?</a></strong></p>
            <?php endif; ?>
            
        </div>
    </div>
    <div class="span4">
        
    </div>
</div> 
<?php echo foot(); ?>
