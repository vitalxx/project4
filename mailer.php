<?php

//$link = mysqli_connect("localhost", "root", "root", "project4");
$dbhost  = "localhost";
$dbuser  = "root";
$dbpass  = "root";
$dbtable = "project4";
$link = new mysqli($dbhost, $dbuser, $dbpass, $dbtable);
  if($link->connect_error)
  {
    echo "Db not connecting. Error: " . $link->connect_error;
    exit();
  }
  
  $query = "SELECT * FROM messages";
  
  $result = $link->query($query);
  
  if($result->num_rows > 0)
  {
    echo "Messages to send:<br>";
    while($row = $result->fetch_assoc())
    {
      $time = explode(":", $row['scheduled_time']);
      $sendIt = 0;
      
      if($time[0] == date("F"))
      {
        $sendIt++;
      }
      
      if($time[1] == date("d"))
      {
        $sendIt++;
      }
     
      if($time[2] == date("Y"))
      {
        $sendIt++;
      }
      
      if($time[3] <= date("g"))
      {
        $sendIt++;
      }
      
      if($time[4] <= date("i"))
      {
        $sendIt++;
      }
     
      if($time[5] == date("a"))
      {
        $sendIt++;
      }
      
      if($sendIt == 6)
      {
        $headers = 'From: ' . $row['username'] . "\r\n" .
        'Reply-To: ' . $row['username'] . "\r\n";
          echo "mailing...";
          mail($row['recipient'], "You have a new message from " . $row['username'], $row['msg'], $headers);
      }
      
    }
  }
  else
  {
    echo "no results";
  }
  
  $link->close();