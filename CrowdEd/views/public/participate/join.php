<?php 
    echo head();
    if (current_user()) {
        $user = current_user();
        $entity = new Entity;
        $entity->getEntityFromUser($user);
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
            <div class="site-header" style="text-align:center"><h1><i class="icon-group"></i> Participate! <small>Create an account</small></h1></div>
            <p class="lead" style="text-align:center"><em>Be a part of the <?php echo get_option('site_title'); ?></em></p>
            <div class="row">
                <div class="span5 offset1">
                    <p class="alert alert-warning text-center"><strong><i class="icon-warning-sign"></i> Already have an account?</strong><br />
                        <?php echo link_to('users', 'login', __('Log in')); ?>
                        or <?php echo link_to('users', 'forgot-password', 'recover a forgotten password'); ?>.
                    </p>
                </div>
            </div>
        </div>
        <div class="span5">
            <?php echo $this->form; ?>
        </div>
    </div>

<?php echo foot() ?>