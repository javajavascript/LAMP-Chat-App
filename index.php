<!doctype html>
<html>
  <head>
    <title>Let's Chat</title>

    <!-- bring in the jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- custom styles -->
    <style>
      #chat_log {
        display:inline-block;
        width: 500px;
        height: 300px;
      }
      .hidden {
        display: none;
      }
      #users {
        height: 300px;
        width: 80px;
      }
      #message {
        width: 480px;
      }
    </style>
  </head>
  <body>
    <h1>Let's Chat</h1>

    <?php
      // if ($_COOKIE['loggedin']) {
      if ( isset($_COOKIE['PHPSESSID']) ) {
        // start up the session (make available any variables in the $_SESSION superglobal)
        session_start();
    ?>
        <div>You are logged in as <?php print $_SESSION['username']; ?></div>
        <a href="logout.php">Log out</a>
        <br>

        <div id="error"></div>
        
        <select id="chat_room">
          <option value="1">Chat Room 1</option>
          <option value="2">Chat Room 2</option>
        </select>

        <div id="panel_chat">
          <textarea readonly id="users"></textarea>
          <textarea readonly id="chat_log"></textarea>
          <br>
          <input type="text" id="message">
          <button id="button_send">Send Message</button>
          <p>Note: Restricted words are "apple" and "pear"</p>
        </div>

    <?php
      }
      else if ($_GET['loginError']) {
    ?>
        <div>Invalid credentials, please try again</div>
    <?php
      }
      else if ($_GET['registerError']) {
    ?>
      <div>Error: username must be 5 characters and password must be 1 character!</div>
    <?php    
      }
      else {
    ?>
      <form id="panel_name" action="login.php">
        Name: <input type="text" name="username" id="username">
        <br>
        Password: <input type="text" name="password" id="password">
        <br>
        <button id="register" name="register">Register</button>
        <button id="login" name="login">Login</button>
      </form>
    <?php
      }
    ?>

    <!-- login system doesn't allow name change -->
    <!-- <div id="change_name" class="hidden">
      <input type="text" id="newname">
      <button id="button_change">Change Name</button>
    </div> -->

    <script>
      // let selectedName;
      $(document).ready(function() {
        // DOM refs
        let panel_name = document.getElementById('panel_name');
        let username = document.getElementById('username');
        let register = document.getElementById('register');
        let panel_chat = document.getElementById('panel_chat');
        let chat_log = document.getElementById('chat_log');
        let message = document.getElementById('message');
        let button_send = document.getElementById('button_send');

        let error = document.getElementById('error');
        let change_name = document.getElementById('change_name');
        let newname = document.getElementById('newname');
        let button_change = document.getElementById('button_change');
        let chat_room = document.getElementById('chat_room');

        let users = document.getElementById('users');

        let usernameFromPHP = '<?php print $_SESSION['username']; ?>'

        //when we switch the chat room, update data immediately
        //data is updated via an interval later in the code, but this improves UX 
        if (chat_room) {
          chat_room.onchange = function() {
            getData();
          }
        }

        //list of restricted words
        let words = [];

        //get restricted words from the server
        function getWords() {
          $.ajax({
            url: 'get_words.php',
            type: 'get',
            data: { //no specific criteria, get all data
              //
            },
            success: function(data, status) {
              let parsed = JSON.parse(data);
              for (let i = 0; i < parsed.length; i++) {
                words.push(parsed[i].word);
              }
            }
          })
        }      

        if (button_send) {
          //send user's message to the server
          button_send.addEventListener('click', function() {
            //check for restricted words
            for (let i = 0; i < words.length; i++) {
              if (message.value.includes(words[i])) {
                error.innerHTML = "Error: Restricted word!";
                return; //exit function, do not make the ajax call
              }
            }
            error.innerHTML = ""; //if valid word, remove error message

            // make an ajax call to the server to save the message
            $.ajax({
              url: 'save_message.php',
              type: 'post',
              data: {
                name: usernameFromPHP,
                message: message.value,
                chat_room: parseInt(chat_room.value)
              },
              //when it's successful we should add the message to the chat log so we can see it
              success: function(data, status) {
                chat_log.value += usernameFromPHP + ': ' + message.value + "\n";
              }
            });
          });

        }

        //get user's message from the server
        function getData() {
          $.ajax({
            url: 'get_messages.php',
            type: 'get',
            data: { //send chat room to get messages for only that chat
              name: usernameFromPHP,
              chat_room: parseInt(chat_room.value)
            },
            success: function(data, status) {
              let parsed = JSON.parse(data);
              let newChatroom = '';
              for (let i = 0; i < parsed.length; i++) {
                newChatroom += parsed[i].name + ': ' + parsed[i].message + "\n";
              }
              chat_log.value = newChatroom;
              setTimeout(getData, 2000); //update the data every 2 seconds
            }
          })
        }

        //get online users
        function getUsers() {
          $.ajax({
            url: 'get_users.php',
            type: 'get',
            data: { 
              //
            },
            success: function(data, status) {
              let parsed = JSON.parse(data);
              console.log(parsed);
              for (let i = 0; i < parsed.length; i++) {
                if (!users.innerHTML.includes(parsed[i].name)) { //prevent duplicates
                  users.innerHTML += parsed[i].name + "\n"; //needs to be += because = will only use the last parsed[i]
                }
              }
              setTimeout(getUsers, 2000); //update the data every 2 seconds
            }
          })
        }

        if (chat_room) {
          getWords();
          getData();
          getUsers();
        }

      });

    </script>

  </body>
</html>