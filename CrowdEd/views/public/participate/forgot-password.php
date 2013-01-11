<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>
<?php
$pageTitle = __('Forgot Password');
echo head(array('title' => $pageTitle, 'bodyclass' => 'login'));
?>
<div class="row">
    <div class="span12">
       <h1><i class="icon-question-sign"></i> <?php echo $pageTitle; ?></h1>
    </div>
    <div class="span12">
        <p class="lead"><?php echo __('Enter your email address to retrieve your password.'); ?></p>
        <?php echo flash(); ?>
        <form method="post" accept-charset="utf-8">
            <div class="field">        
                <label for="email"><i class="icon-envelope"></i> <?php echo __('Email'); ?></label>
                <?php echo $this->formText('email', @$_POST['email'], array('class'=>'textinput')); ?>
            </div>
            <div class="row">
                <div class="span2">
                    <input type="submit" class="submit btn btn-primary" value="<?php echo __('Submit'); ?>" />
                </div>
                <div class="span10">
                    <?php echo link_to('participate','login','Back to login',array('class'=>'text-warning')); ?>
                </div>
            </div>
        </form>
    </div>
</div>
<?php echo foot(); ?>