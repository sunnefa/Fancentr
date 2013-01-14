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
 * The module controller for the private messages module 
 */

$part = (isset($_GET['part'])) ? $_GET['part'] : 'list';

switch($part) {
    case 'list':
        include_once 'view_all_messages.php';
        break;
    
    case 'send':
        include_once 'send_message.php';
        break;
    
    case 'single':
        include_once 'view_single_message.php';
        break;
    
    case 'delete':
        include_once 'delete_message.php';
        break;
}

$middle = new Template();
echo $middle->inject_middle($content);

?>
