<?php 
    echo head();
    
    if (!$user) {
        $user = current_user();
    }
?>
<div class="row">
    <div class="span12">
        <h1><?php echo $this->gravatar($user->email,array('imgSize'=>60)) . ' ' . $user->username; ?> <small>Editor Profile</small></h1>
        <p class="lead"><strong>
        <?php 
            echo $user->name;
            if (trim($user->institution) != '') {
                echo ' from ' . $user->institution;
            }
            if ($user->id == current_user()->id) {
                echo ' <a href="/participate/edit-profile"><i class="icon-edit"></i> Edit</a></strong></p>';
            }
        ?>
        <hr />
    </div>
</div>

<div class="row">
    <div class="span4">
        <div class="well">
            <h3><i class="icon-heart-empty"></i> Favorite Items</h3>
            <ul class="unstyled">
                <?php echo $this->profile()->getUserFavorites($user,10); ?>
            </ul>
            <p><a href="/participate/favorites/user/<?php echo $user->id ?>">
               <?php if ($user == current_user()) {
                   echo 'See all of your favorite items';
               } else {
                   echo 'See all the items favorited by ' . $user->username;
               } ?>
                </a></p>
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
            <?php else: ?>
                <p class="alert alert-info">You haven't edited anything yet. <strong><a href="/getting-started">Why not get started?</a></strong></p>
            <?php endif; ?>
            
        </div>
    </div>
    <div class="span4">
        
    </div>
</div> 
<?php echo foot(); ?>
