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
//Handles user registrations

$user_obj = new User($sql);
//if a user is logged in there is no point in registering
if(!$user_obj->is_logged_in()) {
    //if it's a post request
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = new User($sql);
        $validator = new Validator($_POST['user_data']);
        $valid_data = $validator->get_validated_data();

        //if the data is valid
        if($valid_data['valid'] === TRUE) {
            //if the user could be added
            $reg = $user->add_user($valid_data);
            if($reg === TRUE) {
                //log the user in and redirect to home
                $login = $user->login(array('email_address' => $valid_data['email_address'], 'password' => $valid_data['password']));
                if($login) {
                    reload();
                }
            } elseif(is_array($reg)) {
                //if the username or email address are already registered
                $_SESSION['reg_error'] = $reg;
                $_SESSION['user_data'] = $valid_data;
            } else {
                //if the sql returned false
                $_SESSION['reg_error'] = 'Could not add user due to problem with SQL';
                $_SESSION['user_data'] = $valid_data;
            }
        } else {
            //if the data is not valid
            $_SESSION['reg_error'] = $valid_data;
            $_SESSION['user_data'] = $_POST['user_data'];

        }
        reload('users/register');

    //if it's a get request
    } else {
        //if there was an error
        if(isset($_SESSION['reg_error'])) {
            //if there are many errors
            if(is_array($_SESSION['reg_error'])) {
                $reg_error_html = "";
                //loop through them
                foreach($_SESSION['reg_error'] as $reg_error) {
                    $reg_error_html .= '<p>' . $reg_error . '</p>';
                }
            } else {
                //if there was only one
                $reg_error_html = $_SESSION['reg_error'];
            }
        } else {
            //if there was no error
            $reg_error_html = "";
        }

        //load the reg form template
        $reg_form = new Template("users/registration_form.html");
        //replace the tokens
        $reg_form->parse_tokens(array(
            'REG_ERROR' => $reg_error_html,
            'USERNAME' => (isset($_SESSION['user_data']['username'])) ? $_SESSION['user_data']['username'] : '',
            'EMAIL_ADDRESS' => (isset($_SESSION['user_data']['email_address'])) ? $_SESSION['user_data']['email_address'] : '',
            'PASSWORD' => (isset($_SESSION['user_data']['password'])) ? $_SESSION['user_data']['password'] : '',
            'DISPLAY_NAME' => (isset($_SESSION['user_data']['display_name'])) ? $_SESSION['user_data']['display_name'] : ''

        ));
        //get the parsed template
        $content = $reg_form->return_parsed_template();
    }
} else {
    reload('sites');
}
//remove the reg error and user data from the session
unset($_SESSION['reg_error']);
unset($_SESSION['user_data']);
?>
