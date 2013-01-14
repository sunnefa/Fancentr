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
 * Controller for all post requests in the user's module 
 */

if(isset($_POST['part'])) {
    switch($_POST['part']) {
        case 'add_user':
            include_once 'register_user.php';
            break;
        
        case 'login_user':
            include_once 'login_user.php';
            break;
        
        case 'edit_profile':
            include_once 'edit_profile.php';
            break;
    }
} else {
    //TODO: something better here, like 404
    echo 'Invalid post request';
}
?>
