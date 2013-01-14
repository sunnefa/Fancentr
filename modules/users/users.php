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
 * Module controller for the users module 
 */

$part = (isset($_GET['part'])) ? $_GET['part'] : 'all';

switch($part) {
    case 'all':
        include_once 'view_all_users.php';
        break;
    
    case 'logout':
        include_once 'logout.php';
        break;
    
    case 'login':
        include_once 'login_user.php';
        break;
    
    case 'register':
        include_once 'register_user.php';
        break;
    
    case 'profile':
        include_once 'view_single_user.php';
        break;
}

$middle = new Template();
echo $middle->inject_middle($content);
?>
