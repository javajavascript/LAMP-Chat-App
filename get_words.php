<?php

  // connect to databases
  include('config.php');

  // grab all messages from db
  $sql = "SELECT * FROM words";
  $results = $db->query($sql);

  $return_array = array();

  while ($row = $results->fetchArray()) {
    $result_array = array();
    $result_array['id'] = $row['id'];
    $result_array['word'] = $row['word'];
    array_push($return_array, $result_array);
  }

  print json_encode($return_array);

  // package up and send to client

  exit();
 ?>