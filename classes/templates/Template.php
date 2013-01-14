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
 * Handles the parsing of HTML templates
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Template {
    
    /**
     * The raw HTML code before any parsing takes place
     * @var string 
     */
    private $raw_template;
    
    /**
     * The parsed HTML code after all tokens have been replaced
     * @var string 
     */
    private $parsed_template;
    
    /**
     * Constructor, loads the requested template HTML for parsing
     * @param type $template_name 
     */
    public function __construct($template_name = ""){
        if($template_name) {
            ob_start();

            include TEMPLATES . $template_name;

            $this->raw_template = ob_get_clean();
        }
    }
    
    /**
     * Replaces the template tokens with the given values
     * @param type $tokens_replacements 
     */
    public function parse_tokens($tokens_replacements) {
        $this->parsed_template = replace_tokens($this->raw_template, $tokens_replacements);
    }
    
    /**
     * Returns the parsed template
     * @return type 
     */
    public function return_parsed_template() {
        return $this->parsed_template;
    }
    
    /**
     * Loads the middle template and adds what ever $content is to it
     * @param string $content
     * @return string 
     */
    public function inject_middle($content) {
        $middle = new Template('core/middle_col.html');
        $middle->parse_tokens(array('CONTENT' => $content));
        return $middle->return_parsed_template();
    }
    
    public function top($content, $side) {
        $top = new Template('core/' . $side . '_top.html');
        $top->parse_tokens(array('CONTENT_TOP' => $content));
        return $top->return_parsed_template();
    }
    
    public function bottom($content, $side) {
        $bottom = new Template('core/' . $side . '_bottom.html');
        $bottom->parse_tokens(array('CONTENT_BOTTOM' => $content));
        return $bottom->return_parsed_template();
    }
}

?>
