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
 * Handles the adding of a visit to a site 
 */
//if there is no site id supplied the request is invalid
if(!isset($_GET['site_id'])) {
    //TODO: do something a little better here
    echo 'Invalid request';
} else {
    //the site id
    $site_id = $_GET['site_id'];
    
    //make a new site object with that id
    $site = new Site($sql, $site_id);
    
    //add a +1 visit to that site
    $visit = $site->add_visit($site_id);
    
    //load the url to actually visit the site
    visit($site->site_url);
}

?>
