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
 * Post requests controller 
 * Requires forms to have two identification fields, module and part
 * Module is handled here, part is handled in the module's post controller
 */
if(isset($_POST['module'])) {
    switch($_POST['module']) {
        case 'users':
            include_once MODULES . 'users/user_post_controller.php';
            break;
        
        case 'sites':
            include_once MODULES . 'sites/sites_post_controller.php';
            break;
        
        case 'rss':
            include_once MODULES . 'rss/rss_post_controller.php';
            break;
        
        case 'contact':
            include_once MODULES . 'core/contact.php';
            break;
        
        case 'messages':
            include_once MODULES . 'messages/messages_post_controller.php';
            break;
    }
} else {
    //TODO: add nicer fallback
    echo 'Invalid post request';
}
//setting the $page modules to an empty array to get rid of the notice that it's 
//not set, which interferes with ajax post requests
$page_modules = array();
?>
