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
 * Edits a single user profile
 */

//new user object
$user = new User($sql);

//check if the user is logged in
if($user->is_logged_in()) {
    //if the user who is logged in and the user id from the form are the same
    if($user->user_id == $_POST['userid']) {
        
        //load the user info
        $user_info = $_POST['user'];
        //if there is no password set we must remove that element from the array or the validator won't validate
        if(empty($user_info['password'])) {
            unset($user_info['password']);
        }
        
        //check if we need to upload a new picture
        if(isset($_FILES['display_picture'])) {
            
            //new uploader object
            $upload = new Uploader();
            //gather the file data
            $file_data = $_FILES['display_picture'];
            $file_data['image_folder'] = PUBLIC_HTML . '/img/users';
            $file_data['name'] = md5($user->user_name) . '.' . $upload->get_ext($_FILES['display_picture']['name']);
            
            //check if the upload was successful
            if($err = $upload->upload($file_data) === TRUE) {
                $_SESSION['edit_error'][] = 'Display picture uploaded successfully';
            } else {
                $_SESSION['edit_error'][] = $err;
            }
        }
        
        //validate the information
        $validator = new Validator($user_info);
        $valid_data = $validator->get_validated_data();
        
        //if it was valid
        if($valid_data['valid'] === TRUE) {
            //for the validation to pass we needed the bio to have the name text, here we change it to bio
            $valid_data['bio'] = $valid_data['text'];
        
            //add some data to the valid data that should not have been validated
            $valid_data['display_picture'] = isset($file_data) ? $file_data['name'] : $user->display_picture;
            $valid_data['userid'] = $_POST['userid'];

            //check if we were able to update the user
            $edited = $user->update_user($valid_data);
            if($edited) {
                $_SESSION['edit_error'][] = 'Profile updated';
            } else {
                $_SESSION['edit_error'][] = 'Could not update profile';
            }
        } else {
            //if the data wasn't valid
            $_SESSION['edit_error'] = $valid_data;
        }
        
    }
    //regardless of what happens, we will redirect to the profile page
    reload('users/profile/' . $_POST['username']);
} else {
    //if there is noone logged in we redirect to the site list
    reload('sites/list');
}


?>
