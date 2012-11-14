<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */


head(); ?>

<div class="row">
    <div class="span12">
        <div class="site-title"><h1>Community <small>Help us edit the collection</small></h1></div>
    </div>
</div>
<div class="row">
    <div class="span4">
        <div class="well">
            <h3><i class="icon-dashboard"></i> Berry-o-meter</h3>
            <p class="lead">The number of documents edited by the community from the entire current collection. Help us 
            move forward!</p>
            <?php echo createCompletionMeter(); ?>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h3><i class="icon-trophy"></i> Top Editors <small>by Volume</small></h3>
            <ol>
                <li><span class="label label-info"><i class="icon-user"></i> sschlitz</span></li>
                <li><span class="label label-inverse"><i class="icon-user"></i> gsbodine</span></li>
            </ol>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h3><i class="icon-time"></i> Most Recent Editors</h3>
            <ol>
                <li><span class="label label-inverse"><i class="icon-user"></i> gsbodine</span></li>
                <li><span class="label label-info"><i class="icon-user"></i> sschlitz</span></li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    
</div>


<?php foot(); ?>
