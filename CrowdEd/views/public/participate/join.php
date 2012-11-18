<?php 
    head();
        
    if(!isset($user)) {
        $user = new User;
        $user->setArray($_POST);
    } 
       
?>
    <div class="row">
        <div class="span5 offset1">
            <div class="site-header" style="text-align:center"><h1><i class="icon-group"></i> Participate! <small>Create an account</small></h1></div>
            <p class="lead" style="text-align:center"><em>Be a part of the <?php echo settings('site_title'); ?></em></p>
            <?php echo flash(); ?>
        </div>
        <div class="span5">
        <?php if (!$emailSent): ?>
            <form id="crowded-create-profile-form" action="" method="post" accept-charset="utf-8">
            <fieldset class="well">
            <div class="field">
                <?php echo label('username','Choose a username'); ?>
                <div class="inputs">
                <?php echo text(array('name'=>'username', 'class'=>'textinput', 'size'=>'30','id'=>'username'),$user->username); ?>
                </div>
                <?php echo form_error('username'); ?>
            </div>
            <div class="field">
                <?php echo label('first_name','First Name'); ?>

                <div class="inputs">    
                    <?php 
                        $firstNameValue = ((!empty($user->first_name)) ? $user->first_name : $_POST['first_name']);
                        echo text(array('name'=>'first_name', 'size'=>'30', 'class'=>'textinput', 'id'=>'first_name'), $firstNameValue); 
                    ?>
                </div>

                <?php echo form_error('first_name'); ?>
            </div>
            <div class="field">
                <?php echo label('last_name','Last Name'); ?>
                <div class="inputs">
                    <?php 
                        $lastNameValue = ((!empty($user->last_name)) ? $user->last_name : $_POST['last_name']);
                        echo text(array('name'=>'last_name', 'size'=>'30', 'class'=>'textinput', 'id'=>'last_name'), $lastNameValue); 
                    ?>
                </div>
                <?php echo form_error('last_name'); ?>
            </div>
            <div class="field">
                <?php echo label('email','Email'); ?>
                <div class="inputs">
                <?php 
                    $emailValue = ((!empty($user->email)) ? $user->email : $_POST['email']);
                    echo text(array('name'=>'email', 'class'=>'textinput', 'size'=>'30', 'id'=>'email'), $emailValue); 
                ?>
                </div>
                <?php echo form_error('email'); ?>
            </div>
            <div style="text-align:center">
                <input type="submit" class="submit btn btn-primary pull-left" value="Create My Account!" /> <a href="forgot-password" class="text-warning pull-right"><b><i class="icon-question-sign"></i> Forgot password?</b></a>
            </div>
        </fieldset>
        </form>
            
        <?php else: ?>

        <?php endif ?>
        
        </div>
    </div>



<?php foot() ?>