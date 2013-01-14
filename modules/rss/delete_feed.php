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
 * Deletes a feed (or possibly many if I can make a checkbox thingy work) 
 */

//this should be an ajax thing

if(isset($_GET['feed_id'])) {
    
    $rss = new Feed($sql);
    
    if($rss->delete_feed($_GET['feed_id'])) {
        $_SESSION['message'] = 'Feed deleted';
        reload('rss/list');
    }
    
} else {
    echo 'Invalid feed id';
}

?>
