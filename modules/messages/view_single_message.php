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
 * Shows only the messages in a single message thread 
 */

$user = new User($sql);

if($user->is_logged_in()) {
    if(isset($_GET['message_id'])) {
        $message = new Message($sql, $_GET['message_id']);

        $sender = new User($sql, $message->message_sender);
        
        if($user->user_id == $sender->user_id) {
            $class = 'invisible';
        } else {
            $class = 'normal';
            $message->update_message($message->message_id, 'is_read', 1);
        }
        
        $message_template = new Template('messages/single_message.html');
        $message_template->parse_tokens(array(
            'USERNAME' => $sender->display_name,
            'MESSAGE_TITLE' => $message->message_title,
            'MESSAGE' => nl2p($message->message),
            'USERID' => $sender->user_id,
            'CLASS' => $class,
            'MESSAGE_ID' => $message->message_id,
            'SENT_DATE' => date('d M Y', strtotime($message->date_sent))
        ));

        $content = $message_template->return_parsed_template();

    } else {
        $content = 'Invalid message id';
    }
} else {
    $content = 'You do not have permission to view this page';
}
?>
