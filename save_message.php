<?php

  //NOTE: the database file is called db, the table is called chats, and the chat_room number is called chat_room
  // connect to databases
  include('config.php');

  // get post variables
  $name = $_POST['name'];
  $message = $_POST['message'];
  $chat_room = $_POST['chat_room'];

  // make sure there's a message here
  if (strlen($message) > 0) {

    // add to database
    $message = $db->escapeString(addslashes(htmlspecialchars($message)));

    $sql = "INSERT INTO chats (chat_room, name, message) VALUES ('$chat_room', '$name', '$message')";
    print $sql;
    $db->query($sql);

    print "success";
    exit();
  }

  print "fail";
  exit();
  
  //To view and create sqlite3 files, do the following:
  //cd into the database folder
  //must have sqlite (for Mac) or sqlite3.exe (for PC) installed
  //.\sqlite3 (for PC) or ./sqlite3
  //.open dicussion.db
  //CREATE TABLE chats (id integer primary key autoincrement, chat_room integer, name text, message text);
  //CREATE TABLE words (id integer primary key autoincrement, word text);
  //CREATE TABLE users (id integer primary key autoincrement, username text, password text);
  //CREATE TABLE pings (id integer primary key autoincrement, name text, chat_room integer, time integer);
  //INSERT INTO words (word) VALUES ('apple');
  //INSERT INTO words (word) VALUES ('pear');
  //select * from chats;
  //type control C to quit 

  //How to upload to i6
  //Open powershell on windows
  //ssh dl4422@i6.cims.nyu.edu
  //chmod 777 databases (the folder itself)
  //chmod 777 database.txt or database.db (the file itself)

 ?>
 
