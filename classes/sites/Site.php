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
 * Represents a single site from the database
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Site {
    /**
     * The id of the Site
     * @var int 
     */
    protected $site_id;
    
    /**
     * The title of the site
     * @var string 
     */
    protected $site_title;
    
    /**
     * The date the site was added
     * @var int 
     */
    protected $site_date_added;
    
    /**
     * The description of the site
     * @var string 
     */
    protected $site_description;
    
    /**
     * The url of the site
     * @var string 
     */
    protected $site_url;
    
    /**
     * The number of times the site has been visited through the app
     * @var int
     */
    protected $site_visits;
    
    /**
     * The id of the user who added the site
     * @var int
     */
    protected $site_user_added;
    
    /**
     * Does this site have an rss feed available?
     * @var int
     */
    protected $site_rss_ready;
    
    /**
     * The url of the site's rss feed
     * @var string
     */
    protected $site_feed_url;
    
    /**
     * A reference to the DBWrapper
     * @var DBWrapper 
     */
    protected $db_wrapper;
    
    /**
     * The name of this object's database table 
     */
    private $table_name = 'fancentr__sites';
    
    /**
     * Constructs the site object
     * @param DatabaseWrapper $db_wrapper
     * @param int $site_id 
     * @return void
     */
    public function __construct(DBWrapper $db_wrapper, $site_id = 0) {
        $this->db_wrapper = $db_wrapper;
        
        if($site_id) {
            $this->select_site($site_id);
        }
    }
    
    public function __get($name) {
        return $this->$name;
    }
    
    /**
     * Selects a single site based on an id
     * @param int $site_id 
     * @return void
     */
    private function select_site($site_id) {
        $site = $this->db_wrapper->select_data($this->table_name, '*', 'site_id = ' . $site_id);
        
        if($site) {
            $site = array_flat($site);
            
            $this->site_date_added = $site['date_added'];
            $this->site_description = $site['description'];
            $this->site_feed_url = $site['feed_url'];
            $this->site_id = $site['site_id'];
            $this->site_rss_ready = $site['rss_ready'];
            $this->site_title = $site['title'];
            $this->site_url = $site['url'];
            $this->site_user_added = $site['user_added'];
            $this->site_visits = $site['visits'];
            
        }
    }
    
    /**
     * Adds a site to the database
     * @param array $site_data
     * @return boolean 
     */
    public function add_site($site_data) {
        $date_added = date('Y-m-d H:i:s', time());
        
        $added = $this->db_wrapper->insert_data($this->table_name, array(
            'title' => $site_data['title'],
            'description' => $site_data['description'],
            'date_added' => $date_added,
            'url' => $site_data['url'],
            'user_added' => $site_data['user_id'],
            'rss_ready' => $site_data['rss_ready'],
            'feed_url' => $site_data['feed_url']
        ));
        
        if($added) {
            return $this->db_wrapper->get_insert_id();
        } else {
            return false;
        }
    }
    
    /**
     * Loads a list of site in an order based on $order and $direction
     * @param string $order
     * @param string $direction
     * @return array 
     */
    public function load_site_list($order, $direction) {
        switch($order) {
            case 'alphabetic':
                $order_by = 'title';
                break;
            case 'newest':
                $order_by = 'date_added';
                break;
            case 'popular':
                $order_by = 'visits';
                break;
        }
        
        $site_list = $this->db_wrapper->select_data($this->table_name, '*', null, null, $order_by . ' ' . strtoupper($direction));
        
        if($site_list) return $site_list;
        else return false;
    }
    
    /**
     * Checks if a particular url has already been added
     * @param string $url 
     * @return boolean
     */
    public function is_registered_url($url) {
        $is_registered = $this->db_wrapper->select_data($this->table_name, 'site_id', "url = '$url'");
        if($is_registered) {
            return true;
        }
        return false;
    }
    
    /**
     * Increments the site's visit counter
     * @param int $site_id 
     * @return boolean
     */
    public function add_visit($site_id) {
        $increment = $this->db_wrapper->update_data($this->table_name, array('visits' => 'visits + 1'), "site_id = $site_id", true);
        
        if($increment) return true;
        else return false;
    }
    
    /**
     * Returns the number of sites in the database
     * @return int
     */
    public function num_sites() {
        $num = $this->db_wrapper->select_data($this->table_name, 'COUNT(site_id) AS num_sites');
        $num = array_flat($num);
        return $num['num_sites'];
    }
    
    /**
     * Returns the number of visits to all sites
     * @return int 
     */
    public function num_visits() {
        $num = $this->db_wrapper->select_data($this->table_name, 'COUNT(visits) AS num_visits');
        $num = array_flat($num);
        return $num['num_visits'];
    }
    
    /**
     * Returns the id of the last added site
     * @return int
     */
    public function newest_site_id() {
        $id = $this->db_wrapper->select_data($this->table_name, 'site_id', '', '1', 'date_added DESC');
        $id = array_flat($id);
        return $id['site_id'];
    }
}

?>
