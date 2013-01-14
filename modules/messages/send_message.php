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
 * Handles the sending of a message 
 */

$sender = new User($sql);

if($sender->is_logged_in()) {
    if(!isset($_GET['user_id']) && !isset($_POST['recipient'])) {
        $sending = true;
        include_once MODULES . 'users/view_all_users.php';
    } else {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $recipient = new User($sql, $_POST['recipient']);
            $message = new Message($sql);
            $_POST['message'] = strip_tags($_POST['message']);
            $success = $message->send_message($_POST);
            $_SESSION['success'] = 'Your message to ' . $recipient->user_name . ' has been sent';
            reload('messages/list/received');
        } else {
            //todo: make sure that the user id actually exists
            $recipient = new User($sql, $_GET['user_id']);
            
            if(isset($_GET['message_id'])) {
                $old = new Message($sql, $_GET['message_id']);
                $old_message = $old->message;
                $old_title = 'Re: ' . $old->message_title;
            } else {
                $old_message = '';
                $old_title = '';
            }
            
            $message_form = new Template('messages/message_form.html');
            $message_form->parse_tokens(array(
                'RECIPIENT_NAME' => $recipient->display_name,
                'RECIPIENT_ID' => $recipient->user_id,
                'USER_ID' => $sender->user_id,
                'PARENT_ID' => 0,
                'REPLYING_TO' => 0,
                'OLD_MESSAGE' => $old_message,
                'TITLE' => $old_title
            ));
            $content = $message_form->return_parsed_template();
        }
    }
} else {
    $content = 'You do not have permission to view this page';
}
unset($_SESSION['message_info']);
?>
