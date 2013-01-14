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
 * Get requests controller 
 */
//make a new page object
$page = new Page($sql);

//check if a page by the given name was found (if no name is given htaccess makes it 'home' by default)
if($page->get_page_by_name($_GET['page'])) {
    
    $page->load_page_modules();
    
    $page_modules = $page->page_modules;
} else {
    //TODO: add a 404 page reload
    echo 'Page not found';
}
?>
