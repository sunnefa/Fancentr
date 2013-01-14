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
 * Shows a list of all the messages this user has received 
 */

//new user object
$user = new User($sql);

//is a user logged in
if($user->is_logged_in()) {
    
    //new message object
    $message = new Message($sql);
    
    //are we viewing a list of received or sent messages
    $type = (isset($_GET['type'])) ? $_GET['type'] : 'received';
    
    //we need to select different messages depending on the type
    if($type == 'received') {
        $all_messages = $message->select_messages_by_recipient_id($user->user_id);
    } elseif($type == 'sent') {
        $all_messages = $message->select_messages_by_sender_id($user->user_id);
    }
    
    //initiate the html variable
    $message_list_html = "";
    
    //if there aren't any messages we can't really do a loop
    if(!empty($all_messages)) {
        //loop through the messages
        foreach($all_messages as $single_mess) {
            //depending on the type we need to load a different user
            if($type == 'received') {
                $user_obj = new User($sql, $single_mess['sender_id']);
                $from = 'from';
                $sent_class = 'normal';
                if(empty($single_mess['is_read'])) {
                    $class = 'bold';
                } else {
                    $class = 'normal';
                }
            } elseif($type == 'sent') {
                $user_obj = new User($sql, $single_mess['recipient_id']);
                $from = 'to';
                $class = 'normal';
                $sent_class = 'invisible';
            }
            
            //get the template
            $mess_list_template = new Template('messages/message_list_item.html');
            
            //replace the tokens
            $mess_list_template->parse_tokens(array(
                'MESSAGE_ID' => $single_mess['message_id'],
                'MESSAGE_TITLE' => $single_mess['title'],
                'FROM' => $from,
                'SENDER_NAME' => $user_obj->display_name,
                'CLASS' => $class,
                'SENT_CLASS' => $sent_class,
                'SENDER_ID' => $user_obj->user_id,
                'SENT_DATE' => date('d M Y', strtotime($single_mess['date_sent']))
            ));
            //save the html in the variable
            $message_list_html .= $mess_list_template->return_parsed_template();
        }
    } else {
        $message_list_html = '<li>No messages to show</li>';
    }
    
    //get the template
    $message_list = new Template('messages/message_list.html');
    
    //replace the tokens
    $message_list->parse_tokens(array(
        'TYPE' => ucwords($type),
        'MESSAGE_LIST' => $message_list_html,
        'SUCCESS' => (isset($_SESSION['success'])) ? $_SESSION['success'] : ''
    ));
    
    //send the html to the content variable which is parsed in the module controller
    $content = $message_list->return_parsed_template();
    
} else {
    //if a user is not logged in they don't have permission to view messages
    $content = 'You don not have permission';
}
unset($_SESSION['success']);
?>
