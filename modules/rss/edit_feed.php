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
 * Handles the edit of a single rss feed 
 */
//new user object
$user = new User($sql);

//check if a user is logged in
if($user->is_logged_in()) {
    //if the request was a post request
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        //new feed
        $feed = new Feed($sql, $_POST['feed_id'], false);
        
        //collect the old site ids
        $site_ids = array();
        foreach($feed->feed_sites as $single) {
            $site_ids[] = $single['site_id'];
        }
        
        //gather the site data
        $site_data = $_POST;
        $site_data['old_sites'] = $site_ids;
        
        //update the feed
        $updated = $feed->edit_feed($site_data);
        
        //send the right message
        if($updated) {
            $_SESSION['message'] = 'Feed updated';
        } else {
            $_SESSION['message'] = 'Something went wrong';
        }
        //reload the page
        reload('rss/list');
    } else {
        //if the request is a get request
        if(isset($_GET['feed_id'])) {
            //get the feed
            $feed = new Feed($sql, $_GET['feed_id'], false);
            
            //collect only the ids of the old sites in the feed
            $site_ids = array();
            foreach($feed->feed_sites as $single) {
                $site_ids[] = $single['site_id'];
            }
            
            //make a new site object
            $site = new Site($sql);
            
            //load a list of all sites
            $all_sites = $site->load_site_list('alphabetic', 'asc');
            
            //initiate the html var
            $site_list_html = "";
            //loop through all the sites
            foreach($all_sites as $one) {
                
                //only show sites that are rss ready
                if($one['rss_ready'] == 1) {
                    //check if the site id of the current site is in the feed
                    if(in_array($one['site_id'], $site_ids)) $checked = 'checked';
                    else $checked = '';
                    
                    //load the template
                    $single_site = new Template('rss/site_list_item.html');
                    //replace the tokens
                    $single_site->parse_tokens(array(
                        'SITE_ID' => $one['site_id'],
                        'SITE_TITLE' => $one['title'],
                        'CHECKED' => $checked
                    ));
                    //return the template
                    $site_list_html .= $single_site->return_parsed_template();
                }
            }
            
            //load the site list template
            $site_list_templ = new Template('rss/site_list.html');
            //replace the tokens
            $site_list_templ->parse_tokens(array('SITE_LIST' => $site_list_html));
            
            //load the add form template
            $add_form_html = new Template('rss/add_form.html');
            $add_form_html->parse_tokens(array(
                'SITE_LIST' => $site_list_templ->return_parsed_template(),
                'FEED_NAME' => $feed->feed_title,
                'HEADING' => 'Edit feed',
                'MESSAGE' => 'You are editing ' . $feed->feed_title,
                'BUTTON' => 'Edit feed',
                'PART' => 'edit',
                'FEED_ID' => $feed->feed_id
            ));

            $content = $add_form_html->return_parsed_template();
            
        } else {
            $content = 'Invalid feed id';
        }
    }
//if a user is not logged in they can't view this page
    //there is a question of wether I should do this check on the rss feed page itself instead of checking on every part...
} else {
    //show regform
    $content = 'You don not have permission to view this page';
}

?>
