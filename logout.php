<?php
  // is there a session started?
  if ($_COOKIE['PHPSESSID']) {
    // start the session
    session_start();
    // delete the session cookie
    setcookie( session_name(), "", time()-3600, "/" );
    //clear session from globals
    session_unset();
    // just to be safe, delete the session superglobal as well
    $_SESSION = array();
    // clear session from server
    session_destroy();
    // send them back to the login form
    header('Location: index.php');
  }
  //destroy cookie and send back to the main page
  //setcookie('loggedin', '', time()-3600);
  //header('Location: index.php');

 ?>
