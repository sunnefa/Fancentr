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
 * Represents a single adcode as they are stored in the database
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Ad {
    /**
     * The id of the ad
     * @var int 
     */
    private $ad_id;
    
    /**
     * The title of the ad
     * @var string 
     */
    private $ad_title;
    
    /**
     * The ad code
     * @var string 
     */
    public $ad_code;
    
    /**
     * The table name
     * @var string 
     */
    private $table_name = 'fancentr__ads';
    
    /**
     * Reference to DBWrapper
     * @var DBWrapper 
     */
    protected $db_wrapper;
    
    /**
     * Constructor
     * @param DBWrapper $db_wrapper 
     */
    public function __construct(DBWrapper $db_wrapper) {
        $this->db_wrapper = $db_wrapper;
        
        $this->select_random();
    }
    
    /**
     * Select a single random ad from the database 
     */
    private function select_random() {
        $ad = $this->db_wrapper->select_data($this->table_name, '*', null, 1, 'RAND()');
        if($ad) {
            $ad = array_flat($ad);
            
            $this->ad_code = $ad['adcode'];
            $this->ad_id = $ad['ad_id'];
            $this->ad_title = $ad['ad_title'];
        }
    }
    
}

?>
