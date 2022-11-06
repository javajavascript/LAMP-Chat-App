<?php

  // connect to databases
  include('config.php');

  // grab incoming data
  $username = $_GET['username'];
  $password = $_GET['password'];

  print $username;
  print $password;

  // define our secret 'salt'
  $salt = '12345';
  // has the password along with the 'salt'
  $hashed_password = md5($password . $salt);

  // if clicked register button
  if (isset($_GET['register'])) {
    if (strlen($username) < 5 || !ctype_alnum($username) || strlen($password) < 1) {
      header('Location: index.php?registerError=true');
      exit();
    }
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
    $result = $db->query($sql);
    //on registration, insert into pings with name and time, this gets updated later with sql UPDATE but we need to sql INSERT first
    $now = time()*1000; //convert from seconds to miliseconds
    $sql2 = "INSERT INTO pings (name, chat_room, time) VALUES ('$username', 1, '$now')"; //default chat_room is 1
    error_log(print_r($sql2, TRUE)); 
    $result = $db->query($sql2);
    // set a cookie
    setcookie('loggedin', $username);
    // redirect back to main page
    header('Location: index.php');
  }
  
  // if clicked login button
  if (isset($_GET['login'])) {
    $sql = "SELECT * FROM users WHERE (username = '$username' AND password = '$hashed_password')";
    $result = $db->query($sql)->fetchArray();
    // if there is a result then we can log the user in
    if ($result) {
      //set a cookie, old version
      //setcookie('loggedin', $username);

      // start the PHP session
      session_start();
      // generate a new PHPSESSID cookie name
      session_regenerate_id();
      // set a session value -- this data is being stored on the SERVER,
      // not on the client!
      $_SESSION['loggedin'] = 'yes';
      $_SESSION['username'] = $username;

      header('Location: index.php');
    }
    else {
      // redirect back to main page and show an error message
      header('Location: index.php?loginError=true');
    }
  } 

  exit();

 ?>