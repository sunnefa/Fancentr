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
 * View a list of all users 
 */

$user = new User($sql);

$user_list = $user->load_user_list();

$user_list_item_html = "";

if($user->is_logged_in()) {
    $user_id = $user->user_id;
}

foreach($user_list as $single_user) {
    //we don't want to show the user who is currently logged in
    if($single_user['user_id'] == $user_id) {
        $class = 'invisible';
    } else {
        $class = 'normal';
    }
    $user_list_item_template = new Template('users/user_list_item.html');

    $user_list_item_template->parse_tokens(array(
        'USERNAME' => $single_user['username'],
        'REG_DATE' => date('Y-m-d', strtotime($single_user['date_registered'])),
        'USERID' => $single_user['user_id'],
        'CLASS' => $class,
        'NUM_SITES' => $single_user['num_sites'],
        'DISPLAY_PICTURE' => (!empty($single_user['display_picture'])) ? 'img/users/' . $single_user['display_picture'] : 'img/profile.png',
        'DISPLAY_NAME' => $single_user['display_name']
    ));

    $user_list_item_html .= $user_list_item_template->return_parsed_template();
}

if(isset($sending)) {
    $heading = 'Send message';
    $extra_text = 'Select a user to send a message to';
} else {
    $heading = 'Users';
    $extra_text = '';
}

$user_list_template = new Template('users/user_list.html');

$user_list_template->parse_tokens(array(
    'USER_LIST' => $user_list_item_html,
    'HEADING' => $heading,
    'TEXT' => $extra_text
    ));

$content =  $user_list_template->return_parsed_template();

?>
