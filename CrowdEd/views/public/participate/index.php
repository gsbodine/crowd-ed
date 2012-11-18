<?php head(); ?>
<div class="row">
    <div class="span12">
        <div class="site-title"><h1><i class="icon-group"></i> Participate <small>Help us edit the collection</small></h1></div>
        <hr />
    </div>
</div>

<div class="row">
    <div class="span4">
        <div class="well">
            <h2><i class="icon-user"></i> Become an Editor</h2>
            <p class="lead"><a href="/participate/join"><b>Create an <span class="label label-inverse label-large"><i class="icon-user"></i> editor</span> account for the <?php echo settings('site_title'); ?></b></a></p>
            <p class="alert alert-info">If you already have an account: <a href="/participate/login"><strong><i class="icon-key"></i> Log In!</strong></a></p>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h2><i class="icon-book"></i> Learn How to Edit</h2>
            <p class="lead">Visit our <a href="/getting-started"><em><b>Getting Started</b></em></a> page or view our 
                simple <a href="/getting-started#editing"><em><b>Editing Guidelines</b></em></a>.</p>
            <p class="alert alert-warning"><strong>Don't worry!</strong> Help is available on every page when you're editing!</p>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h2><i class="icon-edit"></i> Begin Editing</h2>
            <ul class="lead unstyled">
                <li><a href="/items/advanced-search"><i class="icon-search"></i> Search</a> for items<br /><br /></li>
                <li><a href="/items/browse"><i class="icon-eye-open"></i> Browse</a> the collection<br /><br /></li>
                <li><a href="/participate/random"><i class="icon-question-sign"></i> Edit a randomly-chosen item</a><br /><br /></li>
            </ul>
        </div>
    </div>
</div>


<?php foot(); ?>