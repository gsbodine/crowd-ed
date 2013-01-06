<?php 
    echo head();
    if (current_user()) {
        $user = current_user();
    } else {
        $user = new User;
    }
    $entity = new Entity;
    $entity->getEntityByUser($user);
?>
    <div class="row">
        <div class="span12">
            <?php echo flash(); ?>
        </div>
    </div>
    <div class="row">
        <div class="span5 offset1">
            <div class="site-header" style="text-align:center"><h1><i class="icon-group"></i> Participate! <small>Create an account</small></h1></div>
            <p class="lead" style="text-align:center"><em>Be a part of the <?php echo get_option('site_title'); ?></em></p>
        </div>
        <div class="span5">
        <?php echo $this->form; ?>
        
        </div>
    </div>

<?php echo foot() ?>