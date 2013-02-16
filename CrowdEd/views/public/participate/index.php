<?php echo head(); ?>
<div class="row">
    <div class="span12">
        <div class="site-title"><h1><i class="icon-group"></i> Participate <small>Help us edit the collection</small></h1></div>
        <hr />
        <?php echo flash(); ?>
    </div>
</div>

<div class="row">
    <div class="span4">
        <div class="well">
            <h2><i class="icon-user"></i> Become an Editor</h2>
            <hr />
        <?php if (current_user()): ?>
            <p class="alert alert-success lead"><strong><i class="icon-check"></i> Step one: Complete!</strong></p>
            <p><strong>Welcome!</strong> Since you've already got an account, you're an editor whether you know it yet or not. Move on to the next steps, and thanks in advance for contributing to the project!</p>
        <?php else: ?>
            <p class="lead"><a href="/participate/join"><b>Create an <span class="label label-inverse label-large"><i class="icon-user"></i> editor</span> account for the <?php echo get_option('site_title'); ?></b></a></p>
            <p class="alert alert-info"><i class="icon-key"></i> If you already have an account: <a href="/participate/login"><strong>Log In!</strong></a></p>
        <?php endif ?>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h2><i class="icon-book"></i> Learn How to Edit</h2>
            <hr />
            <p class="lead">Visit our <a href="/getting-started"><em><b>Getting Started</b></em></a> page or view our 
                simple <a href="/getting-started#editing"><em><b>Editing Guidelines</b></em></a>.</p>
            <p class="alert alert-warning"><strong><i class="icon-asterisk"></i> Don't worry!</strong> Help is available on every page when you're editing!</p>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h2><i class="icon-edit"></i> Begin Editing</h2>
            <hr />
            <ul class="lead unstyled">
                <li><a href="/items/search"><strong><i class="icon-search"></i> Search</strong></a> for items<br /><br /></li>
                <li><a href="/items/browse"><strong><i class="icon-eye-open"></i> Browse</strong></a> the collection<br /><br /></li>
                <li><a href="/participate/random"><strong><i class="icon-question-sign"></i> Edit a randomly-chosen item</strong></a><br /><br /></li>
            </ul>
        </div>
    </div>
</div>


<?php echo foot(); ?>