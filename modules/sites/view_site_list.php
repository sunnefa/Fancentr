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
 * View a list of sites, sortable by date, name, visits and hearts 
 */

//which sort order is requested?
$order = (isset($_GET['order'])) ? $_GET['order'] : 'newest';

//does it go ascending or descending?
$direction = (isset($_GET['direction'])) ? $_GET['direction'] : 'desc';

//because alphabetic order technically has lowest to highest (with a being lowest), if 'desc' is not specified in
//the $_GET request we default it to asc
if($order == 'alphabetic' && !isset($_GET['direction'])) {
    $direction = 'asc';
}

//make a new site object
$site = new Site($sql);

//load a list of sites in the order and direction of order that was requested
$site_list = $site->load_site_list($order, $direction);

//declare a placeholder variable for the html in the site list
$site_list_html = "";

//loop through the sites
foreach($site_list as $single_site) {
    //make a new template object with the site list item template
    $site_list_item_template = new Template('sites/site_list_item.html');
    
    //get the screenshot from the shrinktheweb object
    $screenshot = new Shrink($single_site['url']);
    
    //replace the template tokens with the actual values from the database
    $site_list_item_template->parse_tokens(array(
        'SITE_TITLE' => $single_site['title'],
        'SITE_URL' => $single_site['url'],
        'SITE_DESC' => (empty($single_site['description'])) ? '&nbsp;&hellip;' : shorten($single_site['description'], 35),
        'SITE_DATE' => date("jS M Y", strtotime($single_site['date_added'])),
        'SITE_ID' => $single_site['site_id'],
        'SITE_URL' => $single_site['url'],
        'SITE_VISITS' => (!empty($single_site['visits'])) ? $single_site['visits'] : 0,
        'SITE_SCREEN' => $screenshot->output()
        ));
    
    //add the parsed template to the placeholder variable
    $site_list_html .= $site_list_item_template->return_parsed_template();
}

//make a new template object with the site list template
$site_list_template = new Template('sites/site_list.html');

//replace the template token with the site list html loaded in the above loop
$site_list_template->parse_tokens(array('SITE_LIST' => $site_list_html, 'SORT_ORDER' => 'sites/list/' . $order));

//echo out the template to show it on the page
$content = $site_list_template->return_parsed_template();
?>