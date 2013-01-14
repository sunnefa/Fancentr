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
 * Represents a single message from the database
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Message {
    
    /**
     * The id of the message
     * @var int 
     */
    private $message_id;
    
    /**
     * The title of the message
     * @var type 
     */
    private $message_title;
    
    /**
     * The user who sent the message
     * @var type 
     */
    private $message_sender;
    
    /**
     * The message itself
     * @var type 
     */
    private $message;
    
    /**
     * The recipient of the message
     * @var type 
     */
    private $message_recipient;
    
    /**
     * The date the message was sent
     * @var type 
     */
    private $date_sent;
    
    /**
     * If the message has been read or not
     * @var type 
     */
    private $is_read;
    
    /**
     * If the message has been replied to
     * @var type 
     */
    private $replied;
    
    /**
     * The id of the parent message
     * @var type 
     */
    private $parent_id;
    
    /**
     * The DBWrapper
     * @var type 
     */
    private $db_wrapper;
    
    /**
     * The name of the table
     * @var type 
     */
    private $table_name = 'fancentr__private_messages';
    
    /**
     * Constructor
     * @param DBWrapper $db_wrapper
     * @param type $message_id 
     */
    public function __construct(DBWrapper $db_wrapper, $message_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($message_id) {
            $this->select_message_by_id($message_id);
        }
    }
    
    public function __get($name) {
        return $this->$name;
    }
    
    /**
     * Selects a single message from the database
     * @param type $message_id
     * @return boolean 
     */
    private function select_message_by_id($message_id) {
        $message = $this->db_wrapper->select_data($this->table_name, '*', 'message_id = ' . $message_id);
        if($message) {
            $message = array_flat($message);
            
            $this->message_id = $message['message_id'];
            $this->message_recipient = $message['recipient_id'];
            $this->message_sender = $message['sender_id'];
            $this->message_title = $message['title'];
            $this->message = $message['message'];
            $this->date_sent = $message['date_sent'];
            $this->is_read  = $message['is_read'];
            $this->replied = $message['replied'];
            $this->parent_id = $message['parent_id'];
            
            //$this->update_message($message['message_id'], 'is_read', 1);
        } else {
            return false;
        }
    }
    
    /**
     * Selects all messages to a recipient
     * @param type $user_id
     * @return boolean 
     */
    public function select_messages_by_recipient_id($user_id) {
        $messages = $this->db_wrapper->select_data($this->table_name, '*', 'recipient_id = ' . $user_id, null, 'date_sent DESC');
        if($messages) return $messages;
        else return false;
    }
    
    /**
     * Selects all messages by a sender
     * @param type $user_id
     * @return boolean 
     */
    public function select_messages_by_sender_id($user_id) {
        $messages = $this->db_wrapper->select_data($this->table_name, '*', 'sender_id = ' . $user_id, null, 'date_sent DESC');
        if($messages) return $messages;
        else return false;
    }
    
    /**
     * Sends a message
     * @param type $message_data
     * @return boolean 
     */
    public function send_message($message_data) {
        $success = $this->db_wrapper->insert_data($this->table_name, array(
            'recipient_id' => $message_data['recipient'],
            'sender_id' => $message_data['sender'],
            'title' => $message_data['title'],
            'message' => $message_data['message'],
            'date_sent' => date('Y-m-d H:i:s',time()),
            'is_read' => 0,
            'replied' => 0,
            'parent_id' => $message_data['parent']
        ));
        if(!empty($message_data['replying_to'])) $this->update_message($message_data['replying_to'], 'replied', 1);
        if($success) return true;
        else return false;
    }
    
    /**
     * Updates a single field in a message (used to update read and replied to)
     * @param type $message_id
     * @param type $field_data
     * @return boolean 
     */
    public function update_message($message_id, $field, $data) {
        $success = $this->db_wrapper->update_data($this->table_name, array($field => $data), 'message_id = ' . $message_id);
        if($success) return true;
        else return false;
    }
    
    /**
     * Deletes a single message
     * @param type $message_id
     * @return boolean 
     */
    public function delete_message($message_id) {
        $success = $this->db_wrapper->delete_data($this->table_name, 'message_id = ' . $message_id);
        if($success) return true;
        else return false;
    }
    
    /**
     * Deletes all messages with a specific parent id
     * @param type $parent_id
     * @return boolean 
     */
    public function delete_message_by_parent_id($parent_id) {
        $success = $this->db_wrapper->delete_data($this->table_name, 'parent_id = ' . $parent_id);
        if($success) return true;
        else return false;
    }
    
    public function num_unread($user_id) {
        $num = $this->db_wrapper->select_data($this->table_name, 'COUNT(message_id) AS num', 'is_read = 0 AND recipient_id = ' . $user_id);
        $num = array_flat($num);
        return $num['num'];
    }
}

?>
