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
        <div class="span6">
            <div class="site-header" style="text-align:center"><h1><i class="icon-user"></i> Profile <small>Update your information</small></h1></div>
            <p class="lead" style="text-align:center"><em><?php echo get_option('site_title'); ?></em></p>
            <div class="well">
                <h2 class="text-center"><?php echo $this->gravatar($user->email,array('imgSize'=>60)) . ' ' . $user->username; ?></h2>
                <hr />
                <p>This site uses <strong><a href="https://en.gravatar.com/">Gravatar</a></strong> for user avatars (the image beside your username above). 
                    It's free and easy to create your own at <a href="https://en.gravatar.com/">gravatar.com</a>. It also works on other sites, 
                    such as WordPress, github, American Idol, and more.</p>
            </div>
        </div>
        <div class="span6">
            
            <?php echo $this->form; ?>
        </div>
    </div>

<?php echo foot() ?>