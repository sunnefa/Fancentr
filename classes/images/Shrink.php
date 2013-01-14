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
 * Loads a screenshot of a site from shrinktheweb.com
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Shrink {
    
    /**
     * The API url
     * @var string 
     */
    private $service_url = "http://images.shrinktheweb.com/xino.php";
    
    /**
     * We are embedding the image in our page, ie not getting an xml response
     * @var int 
     */
    private $embed = 0;
    
    /**
     * The width of the image
     * @var string 
     */
    private $width = "200";
    
    /**
     * The height of the image
     * @var string
     */
    private $height = "150";
    
    /**
     * The API key to access shrinktheweb.com
     * @var string 
     */
    private $api_key = "c66a82eef5f8c37";
    
    /**
     * The secret key to access shrinktheweb.com
     * @var string 
     */
    private $secret_key = "3786b";
    
    /**
     * The folder the thumbnails are kept in
     * @var string 
     */
    private $thumb_folder;
    
    /**
     * The number of days the cached images are kept
     * @var int 
     */
    private $cache_time = 3;
    
    /**
     * The type of images saved
     * @var string 
     */
    private $file_extension = 'jpeg';
    
    /**
     * The url of the site to get the screenshot of
     * @var string 
     */
    private $url;
    
    /**
     * The filename of the cached image
     * @var string 
     */
    private $filename;
    
    /**
     * Constructor, sets a few variable values and starts the request for the screenshot image
     */
    public function __construct($url) {
        $this->thumb_folder = PUBLIC_HTML . 'cache/';
        
        $this->url = $url;
        
        $this->filename = $this->thumb_folder . md5($this->url) .  "." . $this->file_extension;
        
        $this->request();
    }
    
    /**
     * If the image is cached don't get it, but if it's not get the image from shrinktheweb
     * @return boolean 
     */
    private function request() {
        if($this->check_cache()) {
            return true;
        } else {
            
            $request_string = $this->build_request();
            
            $result = $this->curl_request($request_string);
            
            $response = $this->get_xml_response($result);
            
            if($response == 'delivered') {
                $this->embed = 1;
            
                $request = $this->build_request();
                
                $img = $this->curl_request($request);

                file_put_contents($this->filename, $img);
                return false;
                
            } elseif($response == 'queued' || $response == 'noexist') {
                $this->filename = PUBLIC_HTML . 'img/temp.png';
            }
            
        }
    }
    
    /**
     * Returns the response from the shrinktheweb.com server
     * @param string $request_string
     * @return string 
     */
    private function curl_request($request_string) {
        $curl_connect = curl_init();
        curl_setopt($curl_connect, CURLOPT_URL, $this->service_url . $request_string);
        curl_setopt($curl_connect, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_connect, CURLOPT_FOLLOWLOCATION, 1);

        $curl_result = curl_exec($curl_connect);
        curl_close($curl_connect);
        
        return $curl_result;
        
    }
    
    /**
     * Extracts the status of the thumbnail from the XML data
     * @param type $xml_data
     * @return type 
     */
    private function get_xml_response($xml_data) {
        $dom_object = new DOMDocument;
        $loaded_xml = DOMDocument::loadXML($xml_data);
        $imported_xml = simplexml_import_dom($loaded_xml);
        $xml_layout = 'http://www.shrinktheweb.com/doc/stwresponse.xsd';

        return $imported_xml->children($xml_layout)->Response->ThumbnailResult->Thumbnail[1];
    }
    
    /**
     * Build the request string that is sent to shrinktheweb
     * @return string 
     */
    private function build_request() {
        return  "?" .
                "stwembed=" . $this->embed . "&" .
                "stwu=" . $this->secret_key . "&" .
                "stwver=2.0.4" . "&" . 
                "stwxmax=" . $this->width . "&" .
                "stwymax=" . $this->height . "&" .
                "stwaccesskeyid=" . $this->api_key . "&" .
                "stwurl=" . $this->url;
    }
    
    /**
     * Checks if the cached file exists and if it does check if it has expired
     * @return boolean 
     */
    private function check_cache() {
        if(file_exists($this->filename)) {
            if(filemtime($this->filename) > time() - $this->cache_time * 24 * 60 * 60) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Return the img string with the name of the chached screenshot
     * @param int $width
     * @return string 
     */
    public function output($width = 0) {
        $filename = str_replace(PUBLIC_HTML, '', $this->filename);
        
        if($width == 0) {
            $width = $this->width;
        }
        
        return '<img src="' . $filename . '" width="' . $width . ' alt="Screenshot of ' . $this->url . '" />';
    }
}

?>
