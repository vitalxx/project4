<?php

define("SALT", "FH3#%FNDNndJHDJj99920))^");

$email = "balls@yormom.com";
$pass = hash("sha512", SALT . "balls1!");

//$link = mysqli_connect("localhost", "root", "root", "project4");
$link = new mysqli("localhost", "root", "root", "project4");
  if($link->connect_error)
  {
    echo "Db not connecting. Error: " . $link->connect_error;
    exit();
  }
  
  echo "<p>attemping to find username: $email and password $pass</p>";
  $query = "SELECT * FROM users WHERE username = '$email' AND password = '$pass'";
  
  $result = $link->query($query);
  
  print_r($result);
  
  if($result->num_rows > 0)
  {
    while($row = $result->fetch_assoc())
    {
      echo "<div>" . $row['uid'] . " " . $row['username'] . " " . $row['password'] . " " . $row['created'] . "</div>";
    }
  }
  else
  {
    echo "no results";
  }
  
  $link->close();