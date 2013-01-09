<?php
echo head(array('title'=>'Crowd-Ed Plug-in Administration'));
/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>

<h1>Crowd-Ed</h1>
<hr />
<p>This will be the home of the Crowd-Ed administrative utilities.</p>
<p><a href="<?php echo html_escape(url('index/review')); ?>">Review and/or Lock Edited Items</p>
<p><a href="<?php echo html_escape(url('index/flagged')); ?>">Review Flagged Items</a></p>

<?php 
echo foot();
?>
