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
        <h1><?php echo $this->gravatar($user->email,array('imgSize'=>60)) . ' ' . $user->username; ?> <small>Edited Items</small></h1>
        <p class="lead">
        <?php 
            echo '<strong>'. $user->name .'</strong>';
            if (trim($entity->institution) != '') {
                echo ' of ' . $entity->institution;
            }
        ?></p>
        <hr />
    </div>
</div>

<div class="row">
    <div class="span12">
        <table class="table table-striped">
            <caption><h3><i class="icon-edit"></i> All Edited Items</h3></caption>
            <thead>
                <tr>
                    <th width="25%">Thumbnail</th><th width="60%">Item Information</th><th width="15%">Date Made a Favorite</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $this->profile()->getUserEditedItemsAsTable($user); ?>
            </tbody>
        </table>
    </div>
</div> 
<?php echo foot(); ?>
