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
 * Add a site to the database 
 */

//make a new user object
$user = new User($sql);

//check if a use is logged in
if($user->is_logged_in()) {
    
    //check if the post request has been sent
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        //make a new site object
        $site = new Site($sql);
        
        //make a new crawler object
        $crawler = new Crawler();
        
        //validate the url
        $validate = new Validator(array('url' => $_POST['site_url']));
        $valid_data = $validate->get_validated_data();
        
        //if the url is valid
        if($valid_data['valid'] === TRUE) {
            //has the site been added before?
            //if it hasn't we can add it
            if(!$site->is_registered_url($valid_data['url'])) {
                
                //check if the site is on the black list
                //if it's not
                if(!$crawler->is_blacklisted($valid_data['url'])) {
                
                    //are we allowed to crawl this site?
                    //if we can we can start analysing the keywords
                    if($crawler->load_robots($valid_data['url'])) {
                        if($crawler->analyze_keywords($valid_data['url'])) {
                            //if the keywords are valid we can load the site metadata
                            $site_meta = $crawler->load_external_site_data($valid_data['url']);

                            //send the used id of the logged in user with the site info
                            $site_meta['user_id'] = $_POST['user_id'];

                            //send the site url from the form with the site info
                            $site_meta['url'] = $valid_data['url'];

                            //add the site to the database
                            if($site->add_site($site_meta)) {
                                //if we added it successfully
                                echo '<em>' . $site_meta['title'] . '</em> was successfully added to the database';
                            } else {
                                //if something went wrong with the sql
                                echo 'Could not add <em>' . $site_meta['title'] . '</em> to the database';
                            }
                        } else {
                            //if the keyword analyzes failed
                            echo $crawler->wrong_keywords;
                        }
                    } else {
                        //if we do not have permission to crawl this page
                        echo $crawler->no_premission;
                    }
                } else {
                    //if the site is on the blacklist
                    echo $crawler->blacklisted;
                }
                
            } else {
                //if the site has already been added
                echo 'This site has already been added to our database. Please enter another url.';
            }
        } else {
            //if the url was invalid
            foreach($valid_data as $v) {
                echo $v;
            }
        }
        
    //if the request was a get request we show the add site form
    } elseif($_SERVER['REQUEST_METHOD'] == 'GET') {
        $site_form = new Template('sites/add_site.html');
        $site_form->parse_tokens(array('USER_ID' => $user->user_id));
        $content = $site_form->return_parsed_template();
    }
    
//if there is no use logged in they cannot add a site
} else {
    include_once(MODULES . 'users/register_user.php');
}
?>
