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
//Controller for the statistics module

//make a new user object
$user = new User($sql);

//make a new site object
$site = new Site($sql);

//load the template for the single newest site
$newest_site_templ = new Template('sites/newest_site.html');

//get the id of the newest site
$newest_site_id = $site->newest_site_id();

//make a new site object with that id
$newest_site = new Site($sql, $newest_site_id);

//make a new site screencapture object
$newest_screen = new Shrink($newest_site->site_url);

//replace the right tokens in the newest site html
$newest_site_templ->parse_tokens(array(
    'SITE_TITLE' => $newest_site->site_title, 
    'SITE_SCREEN' => $newest_screen->output(150),
    'SITE_URL' => $newest_site->site_url,
    'SITE_ID' => $newest_site->site_id
    ));

//store the newest site html in a variable
$newest_site_html = $newest_site_templ->return_parsed_template();

//load the stats template
$stats = new Template('core/statistics.html');

//replace the right tokens
$stats->parse_tokens(array(
    'NUM_SITES' => $site->num_sites(), 
    'NUM_USERS' => $user->num_users(), 
    'NUM_VISITS' => $site->num_visits(), 
    'USERS_ONLINE' => $user->users_online(), 
    'NEWEST_SITE' => $newest_site_html
    ));

//make a new template to load the right bottom
$right_bottom = new Template();
//echo the template
echo $right_bottom->bottom($stats->return_parsed_template(), 'right');

?>
