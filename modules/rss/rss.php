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
 * Module controller for the RSS feeds module 
 */

$part = (isset($_GET['part'])) ? $_GET['part'] : 'list';

switch($part) {
    
    case 'list':
        include_once 'view_all_feeds.php';
        break;
    
    case 'single':
        include_once 'show_single_feed.php';
        break;
    
    case 'edit':
        include_once 'edit_feed.php';
        break;
    
    case 'delete':
        include_once 'delete_feed.php';
        break;
    
    case 'add':
        include_once 'create_feed.php';
        break;
    
}

$middle = new Template();
echo $middle->inject_middle($content);

?>
