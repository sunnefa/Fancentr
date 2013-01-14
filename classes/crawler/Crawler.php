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
 * Handles all crawling of third party websites
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Crawler {
    /**
     * A temporary html file containing the html source code of a site to be added
     * @var string 
     */
    protected $temp_html_source;
    
    /**
     * The error message we return if the robots.txt analyzes shows we can't crawl the page
     * @var string 
     */
    public $no_premission = "This site has forbidden us to crawl it, we are unable to access any information about it. Please enter another url. If you are the owner of this site, please consider changing your robots.txt to allow us access.";
    
    /**
     * The error message we show if the keyword analyzes failed
     * @var string 
     */
    public $wrong_keywords = "The keywords on this site make it appear to be a non-fansite. We cannot currently allow manual override of this check. Please enter another url. If this site is indeed a fansite, we apologize. Please understand that our crawler is a work in progress and currently only supports English keywords.";
    
    /**
     * The error message we show if the url is blacklisted
     * @var string
     */
    public $blacklisted = "You have entered the url of a site we know is not a fansite such as Google or Facebook. Please enter another url.";
    
    /**
     * A list of sites we will not allow in the database, it is by no means exhaustive but it 
     * might eliminate the need to analyze keywords on every single one of these sites
     * @var array 
     */
    private $BLACKLIST = array(
        'facebook.com',
        'twitter.com',
        'google.com',
        'yahoo.com',
        'tumblr.com',
        'youtube.com',
        'youporn.com',
        'localhost',
        'pinterest.com',
        '9gag.com',
        'damnlol.com',
        'failblog.com',
        'failbook.com',
        'smartphowned.com',
        'fanfiction.net',
        'ebay.com',
        'cracked.com',
        '4chan.com',
        'linkedin.com',
        'etsy.com',
        'flickr.com',
        'guardian.co.uk',
        'fancentr.org',
        'msn.com',
        'live.com',
        'hotmail.com',
        'gmail.com',
        'myspace.com',
        'lifehackr.com',
    );
    
    /**
     * Loads the meta data of a site that is being added
     * @param string $url
     * @return array 
     */
    public function load_external_site_data($url) {
        if(empty($this->temp_html_source)) {
            $this->load_html_source($url);
        }
        $source = file_get_contents($this->temp_html_source);
        $meta_data = get_meta_tags($this->temp_html_source);
        
        $pieces = parse_url($url);
        
        //get the title from the page
        preg_match("/\<title\>(.*)\<\/title\>/", $source, $matches);
        
        if(isset($matches[1])) {
            $meta_data['title'] = $matches[1];
        } else {
            $meta_data['title'] = $pieces['host'];
        }
        
        preg_match("/\<link rel=\"alternate\" type=\"application\/rss\+xml\" title=\"(.*)\" href=\"(.*)\" \/\>/", $source, $match);
        if(isset($match[2])) {
            $meta_data['feed_url'] = $match[2];
            $meta_data['rss_ready'] = 1;
        } elseif(Crawler::url_exists($url . "/feed")) {
            $meta_data['feed_url'] = $url . "/feed";
            $meta_data['rss_ready'] = 1;
        } else {
            $meta_data['feed_url'] = '';
            $meta_data['rss_ready'] = 2;
        }
        
        if(!isset($meta_data['description'])) {
            //check if there is a welcome text
            if(preg_match("/(Welcome to (.*))/", $source, $math)) {
                $meta_data['description'] = (isset($math[1])) ? strip_tags($math[1]) : '';
                //if not just get the text in the first p tag, which is not perfect but might work in some cases
            } elseif(preg_match("/\<p(.*)\>(.*)/", $source, $mtah)) {
                $meta_data['description'] = (isset($mtah[0])) ? strip_tags($mtah[0]) : '';
            }
        }
        
        unlink($this->temp_html_source);
        
        return $meta_data;
        
    }
    
    /**
     * Saves the html source code of the site being added in a local html file
     * @param string $url 
     */
    private function load_html_source($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT,
      'Fancentr.org (http://www.fancentr.org/crawler/)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        
        $pieces = parse_url($url);
        $this->temp_html_source = PUBLIC_HTML . 'sites_html/' . $pieces['host'] . '.html';
        file_put_contents($this->temp_html_source, $data);
    }
    
    /**
     * Check if a site has a robots.txt and return the results of the analysis of that file
     * @param string $url
     * @return boolean 
     */
    public function load_robots($url) {
        $c = curl_init($url . '/robots.txt');
        curl_setopt($c, CURLOPT_USERAGENT,
      'Fancentr.org (http://www.fancentr.org/crawler/)');
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($c);
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);
        if($status == 200) {
            return $this->analyze_robots($page);
        } else {
            return true;
        }
    }
    
    /**
     * Analyze the contents of the site's robots.txt
     * @param string $robot_contents
     * @return boolean 
     */
    private function analyze_robots($robot_contents) {
        //make the contents of the robots file into an array
        $lines = explode("\n", $robot_contents);
        
        //if the array contains both these lines 'User-agent: *' and 
        //'Disallow: /' it means nobody can crawl anything
        if(in_array('User-agent: *', $lines) && in_array('Disallow: /', $lines)) {
            return false;
            
        //if the array contains a specific command for Fancentr.org
        } elseif(in_array('User-agent: Fancentr.org', $lines)) {
            
            //get the index of that line
            $index = array_search('User-agent: Fancentr.org', $lines);
            
            //get only the lines after the user agent line
            $lines_after = array_slice($lines, $index);
            
            //check if one of those lines disallows Fancentr.org from crawling the page
            if(in_array('Disallow: /', $lines_after)) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
    /**
     * Checks if the site is on the blacklist
     * @param string $url
     * @return boolean 
     */
    public function is_blacklisted($url) {
        $pieces = parse_url($url);
        foreach($this->BLACKLIST as $black) {
            if($pieces['host'] == $black || $pieces['host'] == 'www' . $black) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Checks if the site's front page has keywords to indicate that it is a fansite
     * @param string $url
     * @return boolean 
     */
    public function analyze_keywords($url) {
        if(empty($this->temp_html_source)) {
            $this->load_html_source($url);
        }
        $html = file_get_contents($this->temp_html_source);
        
        //a list of keywords common to fansites
        $keywords = array(
            'gallery',
            'network',
            'affiliates',
            'captures',
            'cast',
            'online',
            'promo',
            'promos',
            'scan',
            'scans',
            'screencaptures',
            'candids',
            'devotee',
            'welcome to',
            'ultimate source',
            '#1 source',
            'HQ resource',
            'hosted by',
            'devotees',
            'fansource',
            'fansite',
            'fan',
            'fans',
            'fan site',
            'fan sites'
        );
        
        //an array of matches so we can count how many there are
        $matches = array();
        
        //loop through the keywords and check if there are any matches
        foreach($keywords as $keyword) {
            if(preg_match("/" . $keyword . "/i", $html, $match)) {
                $matches[] = $match[0];
            }
        }
        
        //if there are more than 3 matches to the keywords we conclude the site is probably a fansite
        if(count($matches) > 3) {
            return true;
        }
        
        //otherwise it's probably not a fansite
        return false;
    }
    
        /**
     * Check if a particular url actually exists
     * @param type $url
     * @return boolean 
     */
    public static function url_exists($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($status == 200 || $status > 300) {
            return true;
        }
        return false;
    }
}

?>
