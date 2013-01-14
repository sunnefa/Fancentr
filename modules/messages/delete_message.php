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

$user = new User($sql);

if($user->is_logged_in()) {
    if(isset($_GET['message_id'])) {
        $message = new Message($sql);
        $success = $message->delete_message($_GET['message_id']);
        $_SESSION['success'] = 'Your message has been deleted';
        reload('messages/list');
    } else {
        $content = 'Invalid message id';
    }
} else {
    $content = 'You do not have permission to view this page';
}

?>
