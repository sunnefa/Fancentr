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
 * Main index, everything is routed through here 
 */

//start a session
session_start();

//start the output buffer
ob_start();

//include the init.php which includes all other neccesary files
if(!require_once("../config/init.php")) {
    die("The init.php file could not be included, please contact the system administrator.");
}

//make a new DBWrapper object
$sql = new MySQLWrapper(MYSQL_DATA, MYSQL_USER, MYSQL_PASS, MYSQL_HOST);

//include the main controller
require_once CONTROLLERS . 'main.php';

//loop through the page modules loaded by the controllers
if(is_array($page_modules)) {
    foreach($page_modules as $module) {
        include ROOT . $module->module_path;
    }
}
//flush the output buffer
ob_end_flush();
?>
