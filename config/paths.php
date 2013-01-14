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
 * Defines the directory paths used in the script 
 */

/**
 * A shortcut to the directory separator 
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * The root directory 
 */
define('ROOT', dirname(dirname(__FILE__)) . DS);

/**
 * The classes directory 
 */
define('CLASSES', ROOT . 'classes' . DS);

/**
 * The controllers directory 
 */
define('CONTROLLERS', ROOT . 'controllers' . DS);

/**
 * The modules directory 
 */
define('MODULES', ROOT . 'modules' . DS);

/**
 * The templates directory 
 */
define('TEMPLATES', ROOT . 'templates' . DS);

/**
 * The public_html directory 
 */
define('PUBLIC_HTML', ROOT . 'public_html' . DS);
?>
