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

$user = new User($sql);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(empty($_POST['username'])) {
        unset($_POST['username']);
    }
    $validate = new Validator($_POST);
    $valid_data = $validate->get_validated_data();
    
    if($valid_data['valid'] === TRUE) {
        
        $to = 'shannen.sekaya@gmail.com';
        $subject = $valid_data['text']['subject'];
        $message = wordwrap($valid_data['text']['message'], 70);
        
        $from = (!isset($valid_data['username'])) ? $valid_data['username'] . ' <' . $valid_data['email_address'] . '>' : $valid_data['email_address'];
        
        $headers = "From: " . $from . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        mail($to, $subject, $message, $headers);
        $_SESSION['message'] = 'Message sent'; 
    } else {
        $_SESSION['errors'] = $valid_data;
        $_SESSION['text'] = $_POST['text'];
    }
    reload('contact');
    
} else {
    
    if(isset($_SESSION['errors'])) {
        if(is_array($_SESSION['errors'])) {
            $errors = '';
            foreach($_SESSION['errors'] as $err) {
                $errors .= '<p>' . $err . '</p>';
            }
        } else {
            $errors = $_SESSION['errors'];
        }
    } else {
        $errors = '';
    }
    
    $contact_template = new Template('core/contact.html');
    $contact_template->parse_tokens(array(
        'LOGGED_IN_MESSAGE' => ($user->is_logged_in()) ? 'Your message will show you are logged in as ' . $user->user_name : '',
        'USER_NAME' => ($user->is_logged_in()) ? $user->user_name : '',
        'EMAIL_ADDRESS' => ($user->is_logged_in()) ? $user->user_email : '',
        'EMAIL_TYPE' => ($user->is_logged_in()) ? 'hidden' : '',
        'EMAIL_CLASS' => ($user->is_logged_in()) ? 'invisible' : '',
        'SENT_MESSAGE' => (isset($_SESSION['message'])) ? $_SESSION['message'] : '',
        'MESSAGE' => (isset($_SESSION['text']['message'])) ? $_SESSION['text']['message'] : '',
        'SUBJECT' => (isset($_SESSION['text']['subject'])) ? $_SESSION['text']['subject'] : '',
        'ERRORS' => $errors
    ));
    
    echo $contact_template->inject_middle($contact_template->return_parsed_template());
}
unset($_SESSION['errors']);
unset($_SESSION['message']);
unset($_SESSION['text']);
?>
