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
 * Module controller for the ads module 
 */

$ads = new Ad($sql);

$ad_code_html = new Template('ads/ad.html');
$ad_code_html->parse_tokens(array('ADCODE' => $ads->ad_code));

$left_bottom = new Template();
echo $left_bottom->bottom($ad_code_html->return_parsed_template(), 'left');
?>
