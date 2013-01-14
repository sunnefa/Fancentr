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
 * Some important functions that do not exactly belong in any single class 
 */

/**
 * Autoloads classes from the classes folder - ALL CLASSES MUST BE IN THE CLASSES FOLDER!!!
 * @param string $class_name 
 */
function __autoload($class_name) {
    $class_folders = array('ads', 'core', 'crawler', 'database', 'images', 'messages', 'rss', 'sites', 'text', 'users', 'templates', 'valid');
    
    foreach($class_folders as $folder) {
        $class_path = CLASSES . $folder . '/' . $class_name . '.php';
        if(file_exists($class_path)) {
            require_once $class_path;
        }
    }
}

/**
 * Turns a multidimensional array into a single dimensional array
 * @param array $array
 * @return array 
 */
function array_flat($array) {
    $single = array();
    foreach($array as $one) {
        foreach($one as $key => $value) {
            $single[$key] = $value;	
        }
    }
    return $single;
}

/**
 * Replaces tokens in a given string with replacements given
 * @param string $text
 * @param array $tokens_replacements
 * @return string 
 */
function replace_tokens($text, $tokens_replacements) {
    foreach($tokens_replacements as $token => $replacement) {
        $text = preg_replace('(\{' . $token . '\})', $replacement, $text);
    }
    
    return $text;
}

/**
 * Shortens a string to the inputed number of words
 * @param string $string
 * @param int $num_words
 * @return string 
 */
function shorten($string, $num_words = 10) {
    $array = explode(" ", $string);
    if(count($array) > $num_words) {
        array_splice($array, $num_words);
        return implode(" ", $array) . "&nbsp;&hellip;";
    } else {
        return $string;
    }
}

/**
 * Redirects to the given url - only to be used for external urls
 * @param string $url 
 */
function visit($url) {
    header("Location: " . $url);
}

/**
 * Reloads or redirects a page - intended for internal redirects
 * @param string $where 
 */
function reload($where = '') {
    $base = "http://" . $_SERVER['SERVER_NAME'] . '/';
    if(!empty($where)) {
        header('Location: ' . $base . $where);
        exit;
    } elseif(!empty($_GET)) {
        $query = implode('/', $_GET);
        header('Location: ' . $base . $query);
        exit;
    } else {
        header('Location: ' . $base);
        exit;
    }
}

/**
 * Decodes entities as UTF-8, then encodes them again using UTF-8 - SimpleXML thing
 * @param string $text
 * @return string 
 */
function clean_up_entities($text) {
    $html = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $string = strip_tags($html);
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Adds p tags to new lines in a string
 * @param string $str
 * @return string 
 */
function nl2p($str) {
    $str = "\t<p>" . preg_replace("(\n|\r)", "</p>$0\t<p>", $str) . "</p>";
    return $str;
}
?>
