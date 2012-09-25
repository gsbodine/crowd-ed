<?php

/* 
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>

Your account for the <?php echo $siteTitle; ?> has been created. Your username is "<?php echo $user->username; ?>". Please click the following link to activate your account:

<?php echo abs_uri(array('controller'=>'users', 'action'=>'activate'), 'default'); ?>?u=<?php echo $activationSlug; ?> 

<?php echo $siteTitle; ?> Administrator
