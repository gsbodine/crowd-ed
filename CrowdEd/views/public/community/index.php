<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

echo head();
?>
<div class="row">
    <div class="span12">
        <div class="site-title"><h1 style="display:inline">Community <small>Help us edit and catalog the collection</small></h1>
        <?php if (!current_user()) { ?>    
            <a href="/user/register" style="margin:.5em" class="btn btn-success pull-right"><i class="icon-user"></i> Join us!</a>
        <?php } ?>
            <hr />
        </div>
    </div>
</div>
<div class="row">
    <div class="span12">
        <div class="row">
            <div class="span6">
                <img src="<?php echo img('Community/Berryometer175.jpg'); ?>" alt="" style="border-radius:10px" />
                <div class="page-header"><h2 class="text-center"><i class="icon-dashboard"></i> Berryometer</h2></div>
                <p class="lead text-center">Number of documents edited by the community</p>
                <?php echo $this->completionMeter(); ?>
                <p class="padded"><strong>Be a part of the MBDA community:</strong> <a class="btn btn-success" href="/items/show/<?php echo $this->itemEditing()->getRandomUneditedItem($this->_db)->id; ?>"><i class="icon-edit"></i> Edit a document</a></p>
                
            </div>
            <div class="span3">
                <div class="well top-list">
                    <h3 class="text-center"><i class="icon-trophy"></i> Top Editors </h3>
                    <ul class="unstyled user-list">
                      <?php echo $this->crowdEditors()->getEditorsByVolume($this->_db,10); ?>
                    </ul>
                </div>
            </div>
            <div class="span3">
                <div class="well top-list">
                    <h3 class="text-center"><i class="icon-time"></i> Latest Editors</h3>
                    <ul class="unstyled user-list">
                        <?php echo $this->crowdEditors()->getMostRecentEditors($this->_db,10); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="span6">
        <div class="text-center">
            <a class="twitter-timeline"  href="https://twitter.com/BerryArchive"  data-widget-id="299012645489614850">Tweets by @BerryArchive</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    
        </div>
    </div>
    <div class="span6">
        <h3><i class="icon-heart-empty"></i> Top Community Favorites</h3>
        <?php 
            echo $this->favorites()->listMostFavoritedItems($this->_db);
        ?>
    </div>
    
</div>
<?php echo foot(); ?>
