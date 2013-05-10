<?php echo head(); ?>
<div class="row">
    <div class="span12">
        <div class="site-title"><h1>Participate <small>Help us catalog the collection</small></h1></div>
        <hr />
        <?php echo flash(); ?>
    </div>
</div>

<div class="row">
    <div class="span4" id="participate-become-editor">
        <div class="well">
            <h3 class="text-center"><i class="icon-user"></i> Become an Editor</h3>
            <hr />
        <?php if (current_user()): ?>
            <p class="alert alert-success"><strong><i class="icon-check"></i> Step one: Complete!</strong></p>
            <p><strong>Welcome!</strong> Since you've already got an account, you're an editor whether you know it yet or not. Move on to the next steps, and thanks in advance for contributing to the project!</p>
        <?php else: ?>
            <p><a href="/user/register"><b>Create an <span class="label label-inverse label-large"><i class="icon-user"></i> editor</span> account for the <?php echo get_option('site_title'); ?></b></a></p>
            <p class="alert alert-info"><i class="icon-key"></i> If you already have an account: <a href="/users/login"><strong>Log In!</strong></a></p>
        <?php endif ?>
        </div>
    </div>
    <div class="span4" id="participate-learn-how">
        <div class="well">
            <h3 class="text-center"><i class="icon-book"></i> Learn How to Edit</h3>
            <hr />
            <p>Visit our <a href="/get-started"><strong>Get Started</strong></a> page or view our 
                simple <a href="/editing-guidelines"><strong>Editing Guidelines</strong></a>.</p>
            <p class="alert alert-warning"><strong><i class="icon-asterisk"></i> Don't worry!</strong> Help is available on every page when you're editing!</p>
    </div>
    </div>
    <div class="span4" id="participate-begin-editing">
        <div class="well">
            <h3 class="text-center"><i class="icon-edit"></i> Begin Editing</h3>
            <hr />
            <p><a href="/items/show/<?php echo $this->itemEditing()->getRandomUneditedItem($this->_db)->id; ?>"><strong><i class="icon-question-sign"></i> Edit a randomly-chosen document</strong> that needs editing from the collection</a></p>
            <p class="alert alert-info">Edit documents, <a href="/badges"><strong>earn badges</strong></a>! <span class="badge badge-inverse"><i class="icon-bookmark bronze"></i> Editor</span></p>
        </div>
    </div>
</div>


<?php echo foot(); ?>