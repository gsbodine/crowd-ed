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
        <h2>
        <?php 
            echo $user->name;
            if (trim($entity->institution) != '') {
                echo ' of ' . $entity->institution;
            }
            if ($user->id == current_user()->id) {
                echo ' <small class="pull-right"><a href="/user/update-account"><i class="icon-edit"></i> Edit Account Information</a></small>';
            }
        ?>
        </h2>
        <hr />
    </div>
</div>

<div class="row">
    <div class="span8">
        <?php $ranking = $this->crowdEditors()->getEditorRank($user,$this->_db); 
            if ($ranking > 0) {
        ?>
            <h4><i class="icon-trophy"></i> Editing Rank on <?php echo get_option('site_title'); ?>: #<?php echo $ranking ?></h4>

            <hr />
            <?php } ?>
        <h4><i class="icon-folder-open-alt"></i> Items Recently Edited</h4>
        <?php 
            $itemList = $this->profile()->displayLastItemsEditedByUser($user,10);
            if ($itemList) : ?>
            <ul class="unstyled">
                <?php echo $itemList; ?>
            </ul>
            <hr />
            <p class="text-center"><strong><a href="/participate/edited/user/<?php echo $user->id ?>"><i class="icon-list"></i> 
                <?php if ($user == current_user()) {
                    echo ' List all of your edited items';
                } else {
                    echo ' List all of the items edited by ' . $user->username;
                } ?>
                 </a></strong>
             </p>
        <?php else: ?>
            <p class="alert alert-info">
                <?php if ($user == current_user()) {
                    echo 'You haven&rsquo;t  edited anything yet. <strong><a href="/getting-started">Why not get started?</a></strong></p>';
                } else {
                    echo $user->username .' hasn&rsquo;t edited anything yet.'; 
                } ?>
        <?php endif; ?>
            
        
    </div>
    <div class="span4">
        <div class="well">
            <h4><i class="icon-heart-empty"></i> Favorite Items</h4>
            <?php 
            $favList = $this->profile()->getUserFavorites($user,$limit=10);
            if ($favList) : ?>
                <ul class="unstyled">
                    <?php echo $favList; ?>
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
                    } ?>
                    </strong></p>
             <?php endif ?>
        </div>
    </div>
</div> 
<?php echo foot(); ?>
