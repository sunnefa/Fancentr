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
 * Some important settings 
 */

/**
 * MySQL username 
 */
define('MYSQL_USER', 'fans');

/**
 * MySQL databasename 
 */
define('MYSQL_DATA', 'fans');

/**
 * MySQL password 
 */
define('MYSQL_PASS', 'fans');

/**
 * MySQL hostname 
 */
define('MYSQL_HOST', 'localhost');

/**
 * Turn error showing on for debugging purposes 
 */
ini_set('display_errors', true);

/**
 * Set an error handler to log errors 
 * TODO: Create an error handler
 */
//set_error_handler('fancentr_errors', E_ERROR);

?>
