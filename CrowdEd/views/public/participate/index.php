<?php echo head(); ?>
<div class="row">
    <div class="span12">
        <div class="site-title"><h1><i class="icon-group"></i> Participate <small>Help us edit the collection</small></h1></div>
        <hr />
        <?php echo flash(); ?>
    </div>
</div>

<div class="row">
    <div class="span8 offset2">
        <div id="participateCarousel" class="carousel slide">
            <!-- Carousel items -->
            <div class="carousel-inner">
                <div class="active item">
                    <img src="<?php echo img('1950GrandMarch.jpg'); ?>" alt="" />
                    <div class="carousel-caption">
                        <h4>Discover the Real Martha Berry</h4>
                        <p>Berry's life and work as the founder of the Berry Schools is widely documented, 
                            but much less is known about Martha Berry the woman. Who was she? Are the rumors about her love life true? 
                            Was she a feminist? And how did she acquire all that land?  What is the real story? 
                            The Martha Berry Collection is published here in its entirety. Participate in editing and discover 
                            for yourself the real Martha Berry.  
                        </p>
                    </div>
                </div>
                <div class="item">
                    <img src="<?php echo img('BrowseImage.jpg'); ?>" alt="" />
                    <div class="carousel-caption">
                        <h4>Share Your Expertise</h4>
                        <p>Many visitors to MBDA know as much or more about Martha Berry and the Berry Schools as we do, 
                            and we need your help. Every document you edit improves the collection’s searchability and aids 
                            project staff and archive visitors in learning more.
                        </p>
                    </div>
                </div>
                <div class="item">
                    <img src="<?php echo img('1950GrandMarch.jpg'); ?>" alt="" />
                    <div class="carousel-caption">
                        <h4>Unearth Stories</h4>
                        <p>Revive voices from the early twentieth century, in some cases previously lost voices.  Letters published in MBDA reveal authors’ 
                            ties to Rome and Northeast Georgia and to locations across the globe. These letters are often personal, exposing author’s 
                            intimate insights into the national and regional milieu during key historical moments such as WWI and WWII, the women’s 
                            suffrage movement, educational reform, and presidential elections. And many letters serve as artifacts of history and 
                            culture that can inform understanding of politics, diet, travel, medical treatments, advertising, language, and much, much more.
                        </p>
                    </div>
                </div>
                <div class="item">
                    <img src="<?php echo img('BrowseImage.jpg'); ?>" alt="" />
                    <div class="carousel-caption">
                        <h4>Find Family History</h4>
                        <p>Did you know that the collection contains correspondence with over 200 individuals and organizations? You may just find a piece of your 
                            family history here! </p>
                    </div>
                </div>
                <div class="item">
                    <img src="<?php echo img('1950GrandMarch.jpg'); ?>" alt="" />
                    <div class="carousel-caption">
                        <h4>Teach and Learn</h4>
                        <p>We’re developing lesson plans linked to the Common Core. These lessons and activities can be used as is or 
                            modified to enhance social studies and/or language arts curricula. <em>If you develop an MBDA-based lesson plan or 
                            educational activity, let us know. We welcome the opportunity to publish your innovative ideas on MBDA and to 
                            acknowledge your contribution.</em> 
                        </p>
                    </div>
                </div>
                <div class="item">
                    <img src="<?php echo img('BrowseImage.jpg'); ?>" alt="" />
                    <div class="carousel-caption">
                        <h4>Get Recognized</h4>
                        <p>Participant contributions are acknowledged in document citations and through MBDA’s top and recent editor lists. 
                            Get started and get cited! </p>
                    </div>
                </div>
            </div>
            <!-- Carousel nav -->
            <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
            <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="span4">
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
    <div class="span4">
        <div class="well">
            <h3 class="text-center"><i class="icon-book"></i> Learn How to Edit</h3>
            <hr />
            <p>Visit our <a href="/getting-started"><strong>Getting Started</strong></a> page or view our 
                simple <a href="/editing-guidelines"><strong>Editing Guidelines</strong></a>.</p>
            <p class="alert alert-warning"><strong><i class="icon-asterisk"></i> Don't worry!</strong> Help is available on every page when you're editing!</p>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h3 class="text-center"><i class="icon-edit"></i> Begin Editing</h3>
            <hr />
            <ul class="unstyled">
                <li><a href="/items/search"><strong><i class="icon-search"></i> Search</strong></a> for items<br /><br /></li>
                <li><a href="/items/browse"><strong><i class="icon-eye-open"></i> Browse</strong></a> the collection<br /><br /></li>
                <li><a href="/items/show/<?php echo $this->itemEditing()->getRandomUneditedItem($this->_db)->id; ?>"><strong><i class="icon-question-sign"></i> Edit a randomly-chosen item</strong></a></li>
            </ul>
        </div>
    </div>
</div>


<?php echo foot(); ?>