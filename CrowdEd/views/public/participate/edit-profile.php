<?php 
    echo head();
    if (current_user()) {
        $user = current_user();
        $e = new Entity;
        $entity = $e->getEntityFromUser($user);
    } else {
        $user = new User;
    }
    
?>
    <div class="row">
        <div class="span12">
            <?php echo flash(); ?>
        </div>
    </div>
    <div class="row">
        <div class="span7">
            <div class="site-header" style="text-align:center"><h1><i class="icon-user"></i> Profile <small>Update your information</small></h1></div>
            <p class="lead" style="text-align:center"><em><?php echo get_option('site_title'); ?></em></p>
        </div>
        <div class="span5">
            <?php echo $this->form; ?>
        </div>
    </div>

<?php echo foot() ?>