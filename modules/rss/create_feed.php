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
 * Handles the creation of a new rss feed 
 */
$user = new User($sql);

if($user->is_logged_in()) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $feed_data = array(
            'feed_name' => $_POST['feed_name'],
            'user_id' => $user->user_id,
            'sites' => $_POST['sites']
            );
        
        $feed = new Feed($sql);
        
        if($feed->add_feed($feed_data)) {
            $_SESSION['message'] = 'Feed added';
            reload('rss/list');
        } else {
            echo 'Could not add feed';
        }
        
    } else {
        $site = new Site($sql);
        $site_list = $site->load_site_list('alphabetic', 'asc');
        
        $site_list_html = "";
        
        foreach($site_list as $single) {
            if($single['rss_ready'] == 1) {
                $single_site = new Template('rss/site_list_item.html');
                $single_site->parse_tokens(array(
                    'SITE_ID' => $single['site_id'],
                    'SITE_TITLE' => $single['title'],
                    'CHECKED' => ''
                ));
                $site_list_html .= $single_site->return_parsed_template();
            }
        }
        
       $site_list_templ = new Template('rss/site_list.html');
       $site_list_templ->parse_tokens(array('SITE_LIST' => $site_list_html));
       
       $add_form_html = new Template('rss/add_form.html');
       $add_form_html->parse_tokens(array(
           'SITE_LIST' => $site_list_templ->return_parsed_template(),
           'FEED_NAME' => '',
           'HEADING' => 'Add new RSS feed',
           'MESSAGE' => 'Use the form below to add a new RSS feed',
           'BUTTON' => 'Add feed',
           'PART' => 'add',
           'FEED_ID' => ''
       ));
       
       $content = $add_form_html->return_parsed_template();
        
    }
} else {
    $content = 'You don not have permission to view this page';
}

?>
