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
 * The module controller for the footer module 
 */
$in_footer = array('dmca', 'tos', 'privacy', 'crawler', 'cookies', 'contact');

$pages = $page->select_multiple_pages();

$navigation_html = "";

foreach($pages as $single_page) {
    if(in_array($single_page['page_name'], $in_footer)) {
        $footer_nav_template = new Template('core/navigation_list_item.html');
        
        $footer_nav_template->parse_tokens(array(
            'PAGENAME' => $single_page['page_title'],
            'LINK' => $single_page['page_url'],
            'CLASS' => 'footer-nav'
            ));
        
        $navigation_html .= $footer_nav_template->return_parsed_template();
    }
}

$footer_template = new Template('core/footer.html');
$footer_template->parse_tokens(array('NAV' => $navigation_html));
echo $footer_template->return_parsed_template();

?>