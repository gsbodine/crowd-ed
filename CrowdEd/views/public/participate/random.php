<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

echo head();

?>

<div class="row">
    <div class="span12">
        <?php echo $this->profile()->featureUnavailable(); ?>
        <p class="lead">Please <a href="/items/browse"><i class="icon-eye-open"></i> Browse</a> or <a href="/items/search"><i class="icon-search"></i> Search</a></p>
    </div>
</div>



<?php echo foot(); ?>
