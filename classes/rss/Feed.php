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
 * Represents a single RSS feed from the database
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Feed {
    /**
     * The id of the feed
     * @var int
     */
    protected $feed_id;
    /**
     * The id of the user who created the feed
     * @var int
     */
    protected $user_id;
    
    /**
     * The title of the feed
     * @var string 
     */
    protected $feed_title;
    
    /**
     * The sites in the feed
     * @var array 
     */
    protected $feed_sites;
    
    /**
     * An array of the posts in this feed
     * @var array
     */
    protected $feed_site_posts;
    
    /**
     * The date the feed was last updated
     * @var int
     */
    protected $last_updated;
    
    /**
     * A reference to DBWrapper
     * @var DBWrapper
     */
    protected $db_wrapper;
    
    /**
     * Constructs the Feed object
     * @param DBWrapper $db_wrapper
     * @param int $feed_id 
     * @return void
     */
    public function __construct(DBWrapper $db_wrapper, $feed_id = 0, $load_posts = true) {
        $this->db_wrapper = $db_wrapper;
        
        if($feed_id) {
            $this->select_feed($feed_id, $load_posts);
        }
    }
    
    public function __get($name) {
        return $this->$name;
    }
    
    /**
     * Selects a single feed based on its id
     * @param int $feed_id
     * @return void; 
     */
    private function select_feed($feed_id, $load_posts) {
        $feed = $this->db_wrapper->select_data('fancentr__feeds', '*', "feed_id = $feed_id");
        
        if($feed) {
            $feed = array_flat($feed);
            $this->feed_id = $feed['feed_id'];
            $this->feed_title = $feed['feed_name'];
            $this->last_updated = $feed['last_updated'];
            $this->user_id = $feed['user_id'];
            
            $this->feed_sites = $this->load_feed_sites();
            
            if($load_posts === TRUE) $this->feed_site_posts = $this->load_feed_posts();
            else $this->feed_site_posts = array();
        }
    }
    
    /**
     * Loads the sites in the feed
     * @param int $feed_id 
     * @return array
     */
    public function load_feed_sites($feed_id = 0) {
        if($feed_id == 0) $feed_id = $this->feed_id;
        
        $feed_site_join = $this->db_wrapper->build_joins('LEFT', array('fancentr__feeds_sites', 'f'), array('f.site_id', 's.site_id'));
        
        $sites = $this->db_wrapper->select_data(array('fancentr__sites', 's'), 's.site_id, s.title, s.feed_url', 'f.feed_id = ' . $feed_id, null, null, null, $feed_site_join);
        
        if($sites) {
            return $sites;
        } else {
            return false;
        }
    }
    
    /**
     * Loads the posts in the feed
     * @return array;
     */
    private function load_feed_posts() {
        //the dates we are going to order by
        $order_by_dates = array();
        //initializing the posts array
        $posts = array();
        //we need a counter variable because we are putting posts from several sites in the same array
        $i = 0;
        
        //loop through the sites in this feed
        foreach($this->feed_sites as $site) {
            
            //get the raw RSS feed using curl
            $ch = curl_init($site['feed_url']);
            curl_setopt($ch, CURLOPT_USERAGENT,
        'Fancentr.org (http://www.fancentr.org/crawler/)');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $data = curl_exec($ch);
            curl_close($ch);
            
            //try converting it to an XML object
            try {
                $x = @new SimpleXMLElement($data, LIBXML_NOENT);
                //looping through each item in the channel
                foreach($x->channel->item as $item) {
                    
                    //if the post is newer than 7 days old
                    if(strtotime((string) $item->pubDate) > time() - (7 * 24 * 60 * 60)) {
                        //get the post title
                        $posts[$i]['post_title'] = clean_up_entities((string) $item->title);
                        //get the post link
                        $posts[$i]['post_link'] = clean_up_entities((string) $item->link);
                        //get the post date
                        $posts[$i]['post_date'] = date('Y-m-d H:i:s', strtotime((string) $item->pubDate));
                        //get the post description
                        $posts[$i]['post_description'] = clean_up_entities((string) $item->description);
                        //put the date of the post in the order by array
                        $order_by_dates[$i] = strtotime((string) $item->pubDate);
                    }
                    //increment the counter
                    $i++;
                }
            } catch(Exception $e) {
                //exception catching here...
            }
        }
        //sort the posts array in descending order by the dates in the order by array
        array_multisort($order_by_dates, SORT_DESC, $posts);
        
        //update the feed to show when it was last updated
        //here we should possibly check if any of the posts is more recent than the last time the feed was updated and if they're not we don't change this value. That way we can highlight the feed with new posts
        $this->update_feed('last_updated', date('Y-m-d H:i:s', time()));
        
        //return the posts array
        return $posts;
    }
    
    /**
     * Used when updating a single field in the feed - most notably the last updated time
     * @param type $field
     * @param type $data
     * @return boolean 
     */
    private function update_feed($field, $data) {
        $updated = $this->db_wrapper->update_data('fancentr__feeds', array($field => $data), "feed_id = " . $this->feed_id);
        
        if($updated) {
            return true;
        } else return false;
    }
    
    /**
     * Loads a list of feeds for a particular user id
     * @param int $user_id
     * @return boolean; 
     */
    public function load_feed_list($user_id) {
        $feed_list = $this->db_wrapper->select_data('fancentr__feeds', '*', "user_id = $user_id");
        
        if($feed_list) {
            return $feed_list;
        } else return false;
    }
    
    /**
     * Adds a new feed to the database
     * @param array $feed_data
     * @return boolean 
     */
    public function add_feed($feed_data) {
        //add the feed to the database
        $added = $this->db_wrapper->insert_data('fancentr__feeds', array(
            'feed_name' => $feed_data['feed_name'],
            'user_id' => $feed_data['user_id'],
            'last_updated' => date('Y-m-d H:i:s', time())
        ));
        
        if($added) {
            //get the id of the feed we just added
            $feed_id = $this->db_wrapper->get_insert_id();
            $success = false;
            //add the sites in the feed to the database
            foreach($feed_data['sites'] as $site) {
               $success = $this->db_wrapper->insert_data('fancentr__feeds_sites', array(
                    'feed_id' => $feed_id, 
                    'site_id' => $site
                ));
            }
            if($success) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * Edits a feed
     * @param array $feed_data 
     * @return boolean
     */
    public function edit_feed($feed_data) {
        
        //check if there sites have been added to the feed and add the relationship
        foreach($feed_data['sites'] as $site_id) {
            $relation = $this->check_feed_site_relationship($site_id, $feed_data['feed_id']);
            
            //if there is not a relationship we add it
            if(!$relation) {
                $this->add_feed_site_relationship($site_id, $feed_data['feed_id']);
            }
        }
        
        //check if a site has been removed from the feed
        $difference = array_diff($feed_data['old_sites'], $feed_data['sites']);
        
        //if one or more site has, we need to delete that relationship
        if(!empty($difference)) {
            foreach($difference as $diff) {
                $this->remove_feed_site_relationship($diff, $feed_data['feed_id']);
            }
        }
        
        //change the name
        $name = $this->db_wrapper->update_data('fancentr__feeds', array('feed_name' => $feed_data['feed_name']), 'feed_id = ' . $feed_data['feed_id']);
        //return true or false
        if($name) {
            return true;
        } else {
            return false;
        }
    }
    
    private function remove_feed_site_relationship($site_id, $feed_id) {
        $this->db_wrapper->delete_data('fancentr__feeds_sites', 'site_id = ' . $site_id . ' AND feed_id = ' . $feed_id);
    }
    
    private function add_feed_site_relationship($site_id, $feed_id) {
        $this->db_wrapper->insert_data('fancentr__feeds_sites', array('site_id' => $site_id, 'feed_id' => $feed_id));
    }
    
    private function check_feed_site_relationship($site_id, $feed_id) {
        $exists = $this->db_wrapper->select_data('fancentr__feeds_sites', 'site_id', 'site_id = ' . $site_id . ' AND feed_id = ' . $feed_id);
        
        if($exists) {
            return true;
        } else {
            return false;
        }
        
    }
    
    /**
     * Deletes a feed form the database
     * @param int $feed_id 
     * @return boolean
     */
    public function delete_feed($feed_id) {
        $sit = $this->db_wrapper->delete_data('fancentr__feeds_sites', 'feed_id = ' . $feed_id);
        $del = $this->db_wrapper->delete_data('fancentr__feeds', 'feed_id = ' . $feed_id);
        
        if($sit && $del) {
            return true;
        } else {
            return false;
        }
    }
}

?>
