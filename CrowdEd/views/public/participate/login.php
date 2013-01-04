<?php echo head(); ?>

<div class="row">
    <fieldset>
        <div class="span12"><legend><h1>Log in to <?php echo get_theme_option('site_title'); ?></h1></legend></div>
        <div class="span4">
            <p class="lead"><strong>Your participation will help to improve the information on this site.</strong></p>
        </div>
        <div class="span8">
            <form action="/users/login" id="crowded-login-form" method="post" accept-charset="utf-8">
                <div class="field">
                <label for="username"><i class="icon-user"></i> Username</label>
                <div class="inputs">
                    <input type="text" name="username" id="username" class="span3" />
                </div>
                </div>

                <div class="field">
                <label for="password"><i class="icon-key"></i> Password</label>
                <div class="inputs">
                    <input type="password" name="password" id="password" class="span3" />
                </div>
                </div>
                <div class="row">
                    <div class="span2">
                        <input type="submit" class="btn btn-primary" value="Log in" /> 
                    </div>
                    <div class="span6">
                        <?php echo link_to('participate', 'forgot-password', __('Forgot password?'),array('class'=>'text-warning')); ?>
                    </div>
                </div>
            </form>
        </div>
    </fieldset>
</div>

<?php echo foot(); ?>