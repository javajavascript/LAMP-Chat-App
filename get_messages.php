<?php

  $name = $_GET['name'];
  $chat_room = $_GET['chat_room'];

  // connect to databases
  include('config.php');

  // grab all messages from db
  $sql = "SELECT * FROM chats WHERE chat_room = $chat_room";
  $results = $db->query($sql);

  $return_array = array();

  while ($row = $results->fetchArray()) {
    $result_array = array();
    $result_array['id'] = $row['id'];
    $result_array['name'] = $row['name'];
    $result_array['message'] = $row['message'];
    array_push($return_array, $result_array);
  }

  print json_encode($return_array);

  // package up and send to client

  $now = time()*1000;

  // update time time online for each user, based on when they sent a message
  // we do sql INSERT in login.php, which is necessary to do sql UPDATE
  $sql2 = "UPDATE pings SET time='$now', chat_room='$chat_room' WHERE name='$name'";
  //print $sql2;
  error_log(print_r($sql2, TRUE)); 
  $db->query($sql2);

  exit();
 ?>