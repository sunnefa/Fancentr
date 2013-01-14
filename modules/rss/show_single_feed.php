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
 * Shows a single rss feed 
 */

if(isset($_GET['feed_id'])) {
    $feed = new Feed($sql, $_GET['feed_id']);
    
    $post_list_html = "";
    
    foreach($feed->feed_site_posts as $post) {
        $post_list_item = new Template('rss/post_list_item.html');
        
        $post_list_item->parse_tokens(array(
            'POST_LINK' => $post['post_link'],
            'POST_TITLE' => $post['post_title'],
            'POST_DATE' => $post['post_date']
        ));
        
        $post_list_html .= $post_list_item->return_parsed_template();
    }
    
    $post_list = new Template('rss/post_list.html');
    
    $post_list->parse_tokens(array(
        'POST_LIST' => $post_list_html,
        'FEED_TITLE' => $feed->feed_title
        ));
    
    $content = $post_list->return_parsed_template();
} else {
    $content = '<p>Invalid request</p>';
}

?>
