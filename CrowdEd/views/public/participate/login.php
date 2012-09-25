<?php head(); ?>

<div class="row">
    <fieldset>
        <div class="span12"><legend><h3>Log in to <?php echo settings('site_title'); ?></h3></legend></div>
        <div class="span4">
            <h4>Your participation will help to improve the information on this site.</h4>
        </div>
        <div class="span8">
            <form action="/users/login" id="crowded-login-form" method="post" accept-charset="utf-8">
                <div class="field">
                <label for="username">Username</label>
                <div class="inputs">
                    <input type="text" name="username" id="username" />
                </div>
                </div>

                <div class="field">
                <label for="password">Password</label>
                <div class="inputs">
                    <input type="password" name="password" id="password" />
                </div>
                </div>
                <input type="submit" class="btn btn-inverse" value="Log in" />
                <?php // TODO: add a 'forgot password' action/link ?>
            </form>
        </div>
    </fieldset>
</div>

<?php foot(); ?>