<?php head(); ?>

<div class="sixteen columns">
    <fieldset>
        <div class="one-third column alpha">
            <legend><h3>Log in to the Martha Berry Digital Archive</h3></legend>
            <h4>Your participation is vital, and so on.</h4>
        </div>
        <div class="two-thirds column omega">
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
                <input type="submit" value="Log in" />
                <?php // TODO: fix this log in form! Also, add a 'forgot password' action/link ?>
            </form>
        </div>
    </fieldset>
</div>

<?php foot(); ?>