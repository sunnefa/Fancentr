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
 * Controller for logging a user in 
 */

//new user object
$user = new User($sql);

//if there is a user logged in there's no need to login again
if(!$user->is_logged_in()) {

    //if it's a post request
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        //log the user in
        $logged_in = $user->login($_POST['user']);

        //if the user was successfully logged in
        if($logged_in) {
            //reload to home page
            reload();
        } else {
            //reload to page with just login form
            $_SESSION['invalid'] = 'Invalid email or password';
            reload('users/login');
        }
    //if it's a get request
    } elseif($_SERVER['REQUEST_METHOD'] == 'GET') {
        $login_form_template = new Template('users/login_form.html');
        $login_form_template->parse_tokens(array());

        $login_page_template = new Template('users/login_page.html');
        $login_page_template->parse_tokens(array(
            'INVALID' => (isset($_SESSION['invalid'])) ? $_SESSION['invalid'] : '',
            'LOGIN_FORM' => $login_form_template->return_parsed_template()
        ));

        $content = $login_page_template->return_parsed_template();
    }
} else {
    reload('sites');
}
unset($_SESSION['invalid']);
?>
