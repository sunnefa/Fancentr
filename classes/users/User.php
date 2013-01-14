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
 * User class, represents a single user
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class User {
    /**
     * The User's id
     * @var int 
     */
    public $user_id;
    
    /**
     * The User's username
     * @var string
     */
    public $user_name;
    
    /**
     * The User's email address
     * @var string 
     */
    public $user_email;
    
    /**
     * The User's dispay name
     * @var string
     */
    public $display_name;
    
    /**
     * The User's password
     * @var string
     */
    public $password;
    
    /**
     * Has this User been activated?
     * @var int 
     */
    public $is_active;
    
    /**
     * Is this User logged in?
     * @var int
     */
    public $is_logged_in;
    
    /**
     * The date the User registered
     * @var int
     */
    public $date_registered;
    
    /**
     * The user's profile picture
     * @var string 
     */
    public $display_picture;
    
    /**
     * A little text about the user
     * @var string 
     */
    public $bio;
    
    /**
     * A reference to the Database wrapper object
     * @var DBWrapper 
     */
    private $db_wrapper;
    
    /**
     * The name of the database table this object uses
     * @var string 
     */
    private $table_name = 'fancentr__users';
    
    /**
     * Constructor
     * @param DatabaseWrapper $db_wrapper
     * @param int $user_id
     * @return void 
     */
    public function __construct(DBWrapper $db_wrapper, $user_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($user_id) {
            $this->select_user_by_id($user_id);
        }
    }
    
    /**
     * Logs a user in or returns an error
     * @param array $user_data
     * @return boolean 
     */
    public function login($user_data) {
        if(is_array($user_data)) {
            $user_id = $this->is_registered_user($user_data);
            if($user_id != false) {
                $this->db_wrapper->update_data($this->table_name, array('is_logged_in' => '1', 'last_login_from' => $_SERVER['REMOTE_ADDR']), 'user_id = ' . $user_id);
                $this->select_user_by_id($user_id);
                
                $this->create_session($user_id);
                $this->create_cookie($user_id);
                
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Performs several checks to ensure a user is registered
     * @param array $user_data 
     * @return boolean
     */
    private function is_registered_user($user_data) {
        $user_email = $user_data['email_address'];
        $user_password = md5($user_data['password']);
        
        $user = $this->db_wrapper->select_data($this->table_name, array('user_id', 'password', 'active'), "email = '$user_email'");
        if($user) {
            $user = array_flat($user);
            if($user['password'] == $user_password && $user['active'] == 1) return $user['user_id'];
        }
        return false;
    }
    
    /**
     * Checks if an email address is registered
     * @param string $email
     * @return boolean 
     */
    public function is_registered_email($email) {
        $is_registered = $this->db_wrapper->select_data($this->table_name, 'email', "email = '$email'");
        if($is_registered) {
            return true;
        }
        return false;
    }
    
    /**
     * Checks if a particular username is registered
     * @param string $user_name 
     * @return boolean
     */
    public function is_registered_username($user_name) {
        $is_registered = $this->db_wrapper->select_data($this->table_name, 'username', "username = '$user_name'");
        if($is_registered) return true;
        return false;
    }
    
    /**
     * Adds a user to the database
     * @param array $user_data
     * @return boolean 
     */
    public function add_user($user_data) {
        $user_email = $user_data['email_address'];
        $user_name = $user_data['username'];
        $user_display_name = $user_data['display_name'];
        $user_password = md5($user_data['password']);
        
        $date_registered = date('Y-m-d H:i:s', time());
        $is_active = 1;
        $is_logged_in = 1;
        
        $errors = array();
        
        if($this->is_registered_email($user_email)) {
            $errors[] = 'This email address is already registered. Did you forget your password?';
        }
        
        if($this->is_registered_username($user_name)) {
            $errors[] = 'This username is already registered. Did you forget your password?';
        }
        
        if(!empty($errors)) {
            return $errors;
        }
        
        $register = $this->db_wrapper->insert_data($this->table_name, array(
            'email' => $user_email,
            'username' => $user_name,
            'password' => $user_password,
            'display_name' => $user_display_name,
            'date_registered' => $date_registered,
            'active' => $is_active,
            'is_logged_in' => $is_logged_in
        ));
        
        if($register) {
            $user_id = $this->db_wrapper->get_insert_id();
            $this->select_user_by_id($user_id);
            return true;
        }
        return false;
        
    }
    
    public function update_user($user_data) {
        $success = $this->db_wrapper->update_data($this->table_name, array(
            'password' => md5($user_data['password']),
            'display_name' => $user_data['display_name'],
            'bio' => $user_data['bio'],
            'display_picture' => $user_data['display_picture']
        ), 'user_id = ' . $user_data['userid']);
        if($success) return true;
        else return false;
    }
    
    /**
     * Selects a single user from the database based on their id
     * @param int $user_id 
     * @return void
     */
    private function select_user_by_id($user_id) {
        $user = $this->db_wrapper->select_data($this->table_name, '*', 'user_id = ' . $user_id);
        
        if($user) {
            $user = array_flat($user);
            $this->date_registered = $user['date_registered'];
            $this->display_name = $user['display_name'];
            $this->is_active = $user['active'];
            $this->is_logged_in = $user['is_logged_in'];
            $this->password = $user['password'];
            $this->user_email = $user['email'];
            $this->user_id = $user['user_id'];
            $this->user_name = $user['username'];
            $this->display_picture = $user['display_picture'];
            $this->bio = $user['bio'];
        }
    }
    
    public function select_user_by_username($username) {
        $user_id = $this->db_wrapper->select_data($this->table_name, 'user_id', "username = '" . $username . "'");
        $this->select_user_by_id($user_id[0]['user_id']);
    }
    
    /**
     * Checks if a user is logged in and loads their info into the object
     * @return boolean 
     */
    public function is_logged_in() {
        if(isset($_SESSION['fancentr_user'])) {
            $this->select_user_by_id($_SESSION['fancentr_user']['user_id']);
            return true;
        } elseif(isset($_COOKIE['fancentr_user'])) {
            $this->select_user_by_id($_COOKIE['fancentr_user']['user_id']);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Creates a session with the user's id and the time they logged in
     * @param int $user_id 
     */
    private function create_session($user_id) {
        if(!isset($_SESSION['fancentr_user'])) {
            $_SESSION['fancentr_user']['user_id'] = $user_id;
            $_SESSION['fancentr_user']['login_time'] = time();
        }
    }
    
    /**
     * Creates a cookie with the user's id and the time they logged in
     * @param int $user_id 
     */
    private function create_cookie($user_id) {
        $expire = time() + 30 * 24 * 60 * 60;
        if(!isset($_COOKE['fancentr_user'])) {
            setcookie('fancentr_user[user_id]', $user_id, $expire, '/');
            setcookie('fancentr_user[login_time]', time(), $expire, '/');
        }
    }
    
    /**
     * Loads the list of all users in the database
     * @return boolean 
     */
    public function load_user_list() {
        $user_list = $this->db_wrapper->select_data($this->table_name, array(
            'user_id',
            'username',
            'display_name',
            'email',
            'bio',
            'date_registered',
            'is_logged_in',
            'last_login_from',
            'display_picture',
            'password',
            'active',
            '(SELECT COUNT(site_id) FROM fancentr__sites WHERE user_added = user_id) AS num_sites'
        ));
        if($user_list) {
            return $user_list;
        } else {
            return false;
        }
    }
    
    /**
     * Logs the user out and sets their status in the database
     * @return boolean 
     */
    public function logout() {
        $logout = $this->db_wrapper->update_data($this->table_name, array('is_logged_in' => 0), 'user_id = ' . $this->user_id);
        if($logout) {
            $this->destroy_session();
            $this->destroy_cookie();
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Destroy the fancentr session, without destroying the session completely 
     */
    private function destroy_session() {
        unset($_SESSION['fancentr_user']);
        session_destroy();
    }
    
    /**
     * Delete the fancentr cookie 
     */
    private function destroy_cookie() {
        unset($_COOKIE['fancentr_user']);
        $expire = 6000;
        setcookie('fancentr_user[user_id]', '', $expire, '/');
        setcookie('fancentr_user[login_time]', '', $expire, '/');
    }
    
    /**
     * Returns the number of users registered
     * @return int 
     */
    public function num_users() {
        $num = $this->db_wrapper->select_data($this->table_name, 'COUNT(user_id) AS num_users');
        $num = array_flat($num);
        return $num['num_users'];
    }
    
    /**
     * Returns the number of users online
     * @return type 
     */
    public function users_online() {
        $num = $this->db_wrapper->select_data($this->table_name, 'COUNT(is_logged_in) AS num', 'is_logged_in = 1');
        $num = array_flat($num);
        return $num['num'];
    }
}

?>
