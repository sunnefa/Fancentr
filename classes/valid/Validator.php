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
 * Validates an array of data in the format type => data
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Validator {
    
    /**
     * The unvalidated data
     * @var array 
     */
    protected $raw_data;
    
    /**
     * A counter array to keep track of how many invalid data entries there are
     * @var array 
     */
    protected $invalids = array();
    
    /**
     * The validated data
     * @var array
     */
    protected $validated_data = array();
    
    /**
     * The errors that were thrown
     * @var array 
     */
    protected $validation_errors = array();
    
    /**
     * Takes the data and sends it to the validation function
     * @param type $data 
     */
    public function __construct($data) {
        $this->raw_data = $data;
        $this->validate();
    }
    
    /**
     * Returns the validated data or the array of error messages
     * @return array 
     */
    public function get_validated_data() {
        if(count($this->invalids) != 0) {
            $this->get_validation_error();
            $this->validation_errors['valid'] = false;
            return $this->validation_errors;
        } else {
            $this->validated_data['valid'] = true;
            return $this->validated_data;
        }
    }
    
    /**
     * Loops through the array of invalid entries and adds an appropriate error message 
     */
    private function get_validation_error() {
        foreach($this->invalids as $type => $data) {
            switch($type) {
                case 'password':
                    $this->validation_errors[] = 'Password must be at least 8 characters and include at least 1 uppercase character, 1 lowercase character and 1 number';
                    break;
                case 'email_address':
                    $this->validation_errors[] = 'This is not a valid email address';
                    break;
                case 'username':
                    $this->validation_errors[] = 'Usernames can only contain alphanumeric characters, no symbols or spaces';
                    break;
                case 'display_name':
                case 'text':
                    $this->validation_errors[] = 'This is not a valid text';
                    break;
                case 'number':
                    $this->validation_errors[] = 'Must contain only numbers, no spaces or alphabetic characters';
                    break;
                case 'url':
                    $this->validation_errors[] = 'That is not a valid URL.';
                    break;
            }
        }
    }
    /**
     * Validates data
     * @return boolean 
     */
    private function validate() {
        if(empty($this->raw_data)) return false;
        foreach($this->raw_data as $type => $data) {
            switch($type) {
                case 'password':
                    if($pass = $this->is_strong_password($data)) {
                        $this->validated_data[$type] = $pass;
                    } else {
                        $this->invalids[$type] = $pass;
                    }
                    break;
                case 'email_address':
                    if($email = $this->is_valid_email($data)) {
                        $this->validated_data[$type] = $email;
                    } else {
                        $this->invalids[$type] = $email;
                    }
                    break;
                case 'username':
                    if($username = $this->is_valid_username($data)) {
                        $this->validated_data[$type] = $username;
                    } else {
                        $this->invalids[$type] = $username;
                    }
                    break;
                case 'display_name':
                case 'text':
                    if($text = $this->is_valid_text($data)) {
                        $this->validated_data[$type] = $text;
                    } else {
                        $this->invalids[$type] = $text;
                    }
                    break;
                case 'number':
                    if($number = $this->is_valid_number($data)) {
                        $this->validated_data[$type] = $number;
                    } else {
                        $this->invalids[$type] = $number;
                    }
                    break;
                case 'url':
                    if($url = $this->is_valid_url($data)) {
                        $this->validated_data[$type] = $url;
                    } else {
                        $this->invalids[$type] = $url;
                    }
                    break;
            }
        }
    }
    
    /**
     * Is it a valid number
     * @param int $number
     * @return boolean/int 
     */
    private function is_valid_number($number) {
        $number = trim($number);
        if(is_numeric($number)) {
            return $number;
        } else {
            return false;
        }
    }
    
    /**
     * Is it valid text
     * @param string $text
     * @return string 
     */
    private function is_valid_text($text) {
        if(is_array($text)) {
            $valid_text = array();
            foreach($text as $key => $tex) {
                $valid_text[$key] = htmlentities(trim($tex));
            }
        } else {
            $valid_text = htmlentities(trim($text));
        }
        return $text;
    }
    
    /**
     * Is it a valid username
     * @param string $username
     * @return boolean/string
     */
    private function is_valid_username($username) {
        $username = trim($username);
        if(preg_match("([-\s!$%^&*()_+|~=`{}\[\]:\";'<>?,.\/])", $username)) {
            return false;
        } else {
            return $username;
        }
    }
    
    /**
     * Is it a good password
     * @param string $password
     * @return boolean/string 
     */
    private function is_strong_password($password) {
        $password = trim($password);
        if(preg_match('(^.*(?=.{8})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$)', $password)) {
            return $password;
        } else {
            return false;
        }
    }
    
    /**
     * Is it a valid email address
     * @param string $email
     * @return boolean/string 
     */
    private function is_valid_email($email) {
        $email = trim($email);
        if(preg_match('(^.*[a-zA-Z0-9-_\.]+@.*[a-zA-Z0-9-_\.]+[\.]+.*[a-z\.]{2,6})', $email)) {
            return $email;
        } else {
            return false;
        }
    }
    
    /**
     * Is it a valid URL
     * @param string $url
     * @return boolean/string
     */
    private function is_valid_url($url) {
        $url = trim($url);
        if(preg_match("/\b(?:(?:https?):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
            if(Crawler::url_exists($url)) 
                return str_replace('www.', '', $url);
        }
        return false;
    }
}

?>