<?php

function crowded_queue_css() {
    queue_css('crowded');
}

function crowded_participate_item() {
    $item = get_current_item();
    echo("<h3>Participate</h3><div><a href=\"/participate/item/". $item->id ."\">Assist us with cataloging this item!</a></div>");
}

function crowded_user_status() {
    
    $user = current_user();
    
    if ($user) {
       $content = "<div id=\"crowded_login_bar\"><a href=\"/participate/profile/". $user->id . "\">" . $user->username . "</a> | <a href=\"" . uri(array('action'=>'logout', 'controller'=>'users'), 'default') . "\">Logout</a></div>";
    } else {
       $content = "<div id=\"crowded_login_bar\"><a href=\"/participate/login\">Log in</a> | <a href=\"/participate/join\">Create Account</a></div>";
    }
    
    echo($content);
}
