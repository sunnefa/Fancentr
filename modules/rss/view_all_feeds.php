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
 * Shows a list of all the rss feeds this user has created 
 */

//new user object
$user = new User($sql);

//if a user is logged in
if($user->is_logged_in()) {
    
    //new feed object
    $rss = new Feed($sql);
    
    //load a list of all feeds
    $feed_list = $rss->load_feed_list($user->user_id);

    //initiate the html variable
    $feed_list_html = "";
    
    //loop through the list
    foreach($feed_list as $single_feed) {
        
        //new list item template
        $feed_list_item_templ = new Template('rss/feed_list_item.html');
        
        //How can I highlight the ones which have new posts?
        
        //replace the tokens
        $feed_list_item_templ->parse_tokens(array(
            'FEED_NAME' => $single_feed['feed_name'],
            'FEED_ID' => $single_feed['feed_id']
        ));
        
        //add the html to the variable
        $feed_list_html .= $feed_list_item_templ->return_parsed_template();
    }
    
    //new template for the list
    $feed_list_templ = new Template('rss/feed_list.html');
    
    //replace tokens
    $feed_list_templ->parse_tokens(array(
        'FEED_LIST' => $feed_list_html,
        'MESSAGE' => (isset($_SESSION['message'])) ? $_SESSION['message'] : ''
    ));
    
    //content is used by the rss module controller
    $content = $feed_list_templ->return_parsed_template();

//if the user is not logged in he does not have permission to be here
} else {
    //show the registration form
    echo 'You do not have permission to view this page.';
}

unset($_SESSION['message']);
?>
