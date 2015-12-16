<?php

//$link = mysqli_connect("localhost", "root", "root", "project4");
$link = new mysqli("localhost", "root", "root", "project4");
  if($link->connect_error)
  {
    echo "Db not connecting. Error: " . $link->connect_error;
    exit();
  }
  
  $query = "SELECT * FROM messages";
  
  $result = $link->query($query);
  
  if($result->num_rows > 0)
  {
    echo "Messages to send in the next half hour:<br>";
    while($row = $result->fetch_assoc())
    {
      $time = explode(":", $row['scheduled_time']);
      
      //echo "<div>" . $row['username'] . " " . $row['msg'] . " " . $row['scheduled_time'] . "</div>";
      
      if($time[0] == date("F") && $time[1] == date("d") && $time[2] == date("Y") && $time[3] == date("g") && date("i") <= $time[4] && $time[5] == date("a"))
      {
        echo "<div>" . $row['username'] . " " . $row['msg'] . " " . $row['scheduled_time'] . "</div>";
      }
      
    }
  }
  else
  {
    echo "no results";
  }
  
  $link->close();