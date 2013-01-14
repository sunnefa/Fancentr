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
 * Represents a single module
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Module {
    /**
     * The id of the module
     * @var int 
     */
    public $module_id;
    
   /**
    * The name of the module
    * @var string
    */
    public $module_name;
    
    /**
     * The path to the module controller file
     * @var string
     */
    public $module_path;
    
    /**
     * A boolean indicating wether this module is active or not
     * @var int 
     */
    public $module_is_active;
    
    /**
     * A reference to DBWrapper
     * @var DBWrapper 
     */
    protected $db_wrapper;
    
    /**
     * The name of the database table this object uses
     * @var string 
     */
    private $table_name = 'fancentr__modules';
    
    /**
     * Constructor, accepts DBWrapper and an optional module id
     * @param DBWrapper $db_wrapper
     * @param int $module_id 
     */
    public function __construct(DBWrapper $db_wrapper, $module_id = null) {
        $this->db_wrapper = $db_wrapper;
        
        if($module_id) {
            $this->select_single_module($module_id);
        }
    }
    
    /**
     * Select a single module
     * @param int $module_id 
     */
    private function select_single_module($module_id) {
        $module = $this->db_wrapper->select_data($this->table_name, '*', "module_id = " . $module_id);
        
        if($module) {
            $module = array_flat($module);
            $this->module_id = $module['module_id'];
            
            $this->module_is_active = $module['module_is_active'];
            
            $this->module_name = $module['module_name'];
            
            $this->module_path = $module['module_path'];
        } else {
            echo 'No modules found';
        }
    }
}

?>
