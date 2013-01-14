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
 * The module controller for the main right side navigation module 
 */

$user = new User($sql);

if($user->is_logged_in()) {
    $pages_in_nav = array('Sites' => 'sites', 'Add site' => 'sites/add', 'RSS feeds' => 'rss', 'Users' => 'users/all');
} else {
    $pages_in_nav = array('Sites' => 'sites', 'Register' => 'users/register');
}

$nav_html = "";

foreach($pages_in_nav as $page_name => $nav_item) {
    $nav_item_templ = new Template('core/navigation_list_item.html');
    $nav_item_templ->parse_tokens(array(
        'CLASS' => str_replace('/', '-', $nav_item),
        'LINK' => $nav_item,
        'PAGENAME' => $page_name
    ));
    
    $nav_html .= $nav_item_templ->return_parsed_template();
}

$nav = new Template('core/navigation.html');
$nav->parse_tokens(array('NAVIGATION' => $nav_html));

$right_top = new Template();
echo $right_top->top($nav->return_parsed_template(), 'right');
?>
