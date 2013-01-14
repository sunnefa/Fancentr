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
 * Shows a single user's profile 
 */
//new user object
$user = new User($sql);
//if a user is logged in
if($user->is_logged_in()) {
    //if there is a username set
    if(isset($_GET['user'])) {
        //another user object - this is the user whose profile we are viewing
        $user_obj = new User($sql);
        //load the user data
        $user_obj->select_user_by_username($_GET['user']);
        
        //if the user who is logged in and the user whose profile we are on are the same
        if($user->user_id == $user_obj->user_id) {
            
            //load the edit messages
            $edit_errors = "";
        
            if(isset($_SESSION['edit_error'])) {
                if(is_array($_SESSION['edit_error'])) {
                    foreach($_SESSION['edit_error'] as $err) {
                        $edit_errors .= '<p>' . $err . '</p>';
                    }
                } else {
                    $edit_errors = '<p>' . $_SESSION['edit_error'] . '</p>';
                }
            }
            
            //show the user that this is their profile
            $logged = 'This is you';
            
            //load the form to edit their profile
            $edit_form_template = new Template('users/profile_form.html');
            //replace the tokens
            $edit_form_template->parse_tokens(array(
                'DISPLAY_NAME' => $user->display_name,
                'BIO' => $user->bio,
                'USERID' => $user->user_id,
                'USERNAME' => $user->user_name,
                'EDIT_ERRORS' => $edit_errors
            ));
            //get the html
            $edit = $edit_form_template->return_parsed_template();
        } else {
            //if it's not the logged in user's profile we don't show anything
            $logged = '';
            $edit = '';
        }

        //load the profile template
        $profile_template = new Template('users/profile.html');
        //replace the tokens
        $profile_template->parse_tokens(array(
            'DISPLAY_NAME' => $user_obj->display_name,
            'DISPLAY_PICTURE' => (!empty($user_obj->display_picture)) ? 'img/users/' . $user_obj->display_picture : 'img/profile.png',
            'BIO' => $user_obj->bio,
            'LOGGED' => $logged,
            'EDIT' => $edit
        ));
        //get the html
        $content = $profile_template->return_parsed_template();

    } else {
        //if there is no user name
        reload('users/all');
    }
} else {
    //if no user is logged in we show a reg form
    include_once MODULES . 'users/register_user.php';
}
unset($_SESSION['edit_error']);
?>
