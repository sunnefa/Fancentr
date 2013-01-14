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
 * Represents a single piece of text from the database
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Text {
    protected $text_id;
    
    public $text;
    
    protected $page_id;
    
    protected $text_name;
    
    private $table_name = 'fancentr__text';
    
    protected $db_wrapper;
    
    public function __construct(DBWrapper $db_wrapper, $page_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($page_id) {
            $this->select_text($page_id);
        }
    }
    
    private function select_text($page_id) {
         $text = $this->db_wrapper->select_data($this->table_name, '*', "page_id = $page_id");
        
        if($text) {
            $text = array_flat($text);
            
            $this->text_id = $text['text_id'];
            
            $this->text_name = $text['text_name'];
            
            $this->text = $text['text'];
            
            $this->page_id = $text['page_id'];
            
        }
    }
}

?>
