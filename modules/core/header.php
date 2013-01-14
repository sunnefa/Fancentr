<?php

/* * *********************
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 * ********************* */
/**
 * The module controller for the header module 
 */

$user = new User($sql);

if($user->is_logged_in()) {
    $logged_in_template = new Template("users/header_info.html");
    $logged_in_template->parse_tokens(array(
        'USERNAME' => $user->user_name,
        'DISPLAY_NAME' => $user->display_name,
        'DISPLAY_PICTURE' => (!empty($user->display_picture)) ? 'img/users/' . $user->display_picture : 'img/profile.png'
                ));
    $leftside_template = $logged_in_template->return_parsed_template();
    
    //add the message stuff to the right
    $message = new Message($sql);
    $unread_messages = $message->num_unread($user->user_id);
    $message_template = new Template('messages/header_info.html');
    $message_template->parse_tokens(array(
        'NUM_MESSAGES' => $unread_messages
    ));
    
    $rightside_template = $message_template->return_parsed_template();
    
} else {
    $login_form_template = new Template('users/login_form.html');
    $login_form_template->parse_tokens(array());
    $leftside_template = $login_form_template->return_parsed_template();
    
    //find something to put to the right when no one is logged in
    $rightside_template = '';
}


$header_template = new Template("core/header.html");

$header_template->parse_tokens(array(
    'PAGE_TITLE' => $page->page_title,
    'PAGE_META' => $page->page_meta_description,
    'LOGGED_IN' => $leftside_template,
    'MESSAGES' => $rightside_template
    ));

echo $header_template->return_parsed_template();

?>
