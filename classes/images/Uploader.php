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
 * Image uploader specifically
 * Could be modified to accept any file for upload but this 
 * site doesn't need uploads of any other kind than images
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class Uploader {
    
    /**
     * The error message when the file is not an image
     * @var type 
     */
    private $not_an_image = 'The file you uploaded is not an image. Please select a different one.';
    
    /**
     * The error message when the upload failed
     * @var type 
     */
    private $upload_error = 'Could not upload image. Try again.';
    
    /**
     * Uploads an image
     * @param type $file_data
     * @return boolean 
     */
    public function upload($file_data) {
        if($this->is_image($file_data['name'])) {
            switch($file_data['error']) {
                case 0:
                    if(move_uploaded_file($file_data['tmp_name'], $file_data['image_folder'] . '/' . $file_data['name'])) {
                        return true;
                    } else {
                        return $this->upload_error;
                    }
                    break;
                
                default:
                    return $this->upload_error;
            }
        } else {
            return $this->not_an_image;
        }
    }
    
    /**
     * Checks if the filename is that of an image
     * @param type $filename
     * @return boolean 
     */
    private function is_image($filename) {
        if(preg_match('(.jpg|.jpeg|.gif|.png)', $filename)) {
            return true;
        }
        return false;
    }
    
    /**
     * Extracts the extension of the file name
     * @param type $name
     * @return type 
     */
    public function get_ext($name) {
        $arr = explode('.', $name);
        return end($arr);
    }
}

?>
