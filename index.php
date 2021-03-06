<?php
session_start();

require("functions.php");
define("SALT", rand());
$loggedIn;
$_SESSION['user'];
$email = $_SESSION['user'];
$submittedMsg = $_POST['submitMsg'];
$reg = $_POST['register'];
$success = "";

//db variables
$dbhost  = "localhost";
$dbuser  = "root";
$dbpass  = "root";
$dbtable = "project4";

//process user login
if (isset($_POST['submitli']))
{
  $email = $_POST['email'];
  $pass   = hash("sha512", SALT . $_POST['password']);
  
  $link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbtable);
  
  if(!$link)
  {
    echo "Db not connecting. Error: " . mysqli_connect_error();
    exit();
  }
  
  $pattern = '/@*[A-Za-z]+\../';
  $match = preg_match($pattern, $email);
  
  if($match != 1)
  {
    echo "Not a valid email: $email";
  }
  else
  {
    //set up query and find if user/pw combination was correct
    $getSalt = "SELECT salt FROM users WHERE username = '$email'";
    $result = mysqli_query($link, $getSalt);
    $res;
    if($result->num_rows > 0) {
      $res = $result->fetch_assoc();
    }
    else {
      echo "Username and password combination incorrect. Try again.";
      exit();
    }
    
    $thisPass = hash("sha512", $res['salt'] . $_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$email' AND password = '$thisPass'";
    $sql = mysqli_query($link, $query);
    
    //see if not selecting for some reason
    if(!$sql)
    {
      echo "Not selectring from db: " . mysqli_error($link);
      exit();
    }
    
    if(mysqli_num_rows($sql) > 0)
    {
      //match found, register user as logged in
      $_SESSION['logged_in'] = true;
      $_SESSION['user'] = $email;
    }
    else
    {
      //no matches found
      $success = "Username and password combination incorrect. Try again.";
    }
    
  }
  
  mysqli_close($link);
}

//register the user as logged in
if (!isset($_SESSION['logged_in']))
{
  $_SESSION['logged_in'] = false;
  $loggedIn = $_SESSION['loggedIn'];
}

if(isset($reg))
{
  $email = $_POST['remail'];
  $salt = SALT;
  $pass = $_POST['rpassword'];

  if(strlen($pass) < 8){
    echo "Password must be at least 8 characters. please try again";
    exit();
  }
  $pass = hash("sha512", $salt . $_POST['rpassword']);
  
  $link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbtable);
  if(!$link)
  {
    echo "Db not connecting. Error: " . mysqli_connect_error();
    exit();
  }
  
  $pattern = '/@*\../';
  $match = preg_match($pattern, $email);
  
  if($match != 1)
  {
    echo "Not a valid email";
  }
  else
  {
    $query = "INSERT INTO users (username, password, salt, created) VALUES ('$email', '$pass', '$salt', NOW());";
    $sql = mysqli_query($link, $query);
    
    if(!$sql)
    {
      echo "Not inserting into db: " . mysqli_error($link);
      exit();
    }
    else
    {
      $_SESSION['logged_in'] = true;
      $_SESSION['user'] = $email;
    }
  }

  mysqli_close($link);
}

//process message to be sent by logged in user
if(isset($submittedMsg))
{
  $recipient      = $_POST['recipient'];
  $hour             = $_POST['hour']; 
  $ampm          = $_POST['ampm'];
  $month          = $_POST['month'];
  $day               = ($_POST['day'] < 10) ? "0" . $_POST['day'] : $_POST['day'];
  $year              = $_POST['year'];
  $date             = "$month:$day:$year";
  $message      = $_POST['message'];
  $timestamp   = "$date:$hour:$ampm";
  
  $link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbtable);
  if(!$link)
  {
    echo "DB not connecting. Error: " . mysqli_connect_error();
    exit();
  }
 
  $error = "";
  
  if($recipient == "")
  {
    $error = "Invalid recipient<br>";
  }
  
  if($hour == "time")
  {
    $error = "Invalid hour<br>";
  }
  
  if($month == "mon")
  {
    $error .= "Invalid month<br>";
  }
  
  if($day == "d")
  {
    $error .= "Invalid day<br>";
  }
  
  if($day == "y")
  {
    $error .= "Invalid year<br>";
  }
  
  if($message == "")
  {
    $error .= "Your message contains no text!";
  }
  
  if($error != "")
  {
    echo $error;
    exit();
  }
  
  //query
  $query = "INSERT INTO messages (username, msg, scheduled_time, recipient) VALUES ('$email', '$message', '$timestamp', '$recipient')";
  $sql = mysqli_query($link, $query);
    
  //check if query works
  if(!$sql)
  {
    echo "Not inserting into db: " . mysqli_error($link);
    exit();
  }
  else
  {
    $dateTime = explode(":", $timestamp);
    $success  = "Email stored and will send to $recipient on " . $dateTime[0] . " "  . $dateTime[1] . ", " . $dateTime[2] . " " . $dateTime[3] . ":" . $dateTime[4] . " " . $dateTime[5];
  }
  
  mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comp 484 - Lab 4</title>    
    <link href="css/reset.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      
      #container
      {
        margin: 15px auto 0;
        width: 500px;
      }
      
      /* for testing only */
      #test
      {
        margin: 15px auto 0;
        padding: 10px;
        width: 500px;
        border: 1px solid red;
      }
      
      #test h2
      {
        font-size: 120%;
        font-weight: bold;
      }
      
      .form-section
      {
        margin-bottom: 15px;
      }
      
      /* Buttons */
      
      .button
      {
        width: 100px;
        height: 35px;
        border: 2px solid #3399ff;
        cursor: pointer;
        color: #333;
        background-color: #fefefe;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
      }
      
      .error
      {
        color: #ff0000;
      }
      
      h1
      {
        font-size: 150%;
        font-weight: bold;
      }
      
      a
      {
        color: #3399ff;
        text-decoration: none;
      }
      
      a:hover
      {
        text-decoration: underline;
      }
      
      a:visited
      {
        
      }
      
    </style>
    
    <!-- jQuery  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    
  </head>
  <body>
    <div id="container">
      
      <?php if (!$_SESSION['logged_in'] && $_GET['p'] != "register"): ?>
        <?php require("login.php"); ?>
      <?php elseif (!$_SESSION['logged_in'] && $_GET['p'] == "register"): ?>
        <?php require("register.php"); ?>
      <?php else: ?>

        <div class="row">
          <div>
            <form name="msg" method="post" action="">
              <div class="form-section">
                <h1>Project 4</h1> <a href="logout.php">Logout</a>
              </div>
              
              <div class="form-section">
                    <label class="sr-only" for="recipient">Recipient: </label><br>
                    <input type="email" name="recipient" class="form-control" id="recipient" size="35" placeholder="Email">
                  </div>

              <div class="form-section">

                <label>Choose the time to send the email</label><br>
                
                
                   <?php 
                      $m = getAllMonths(); 
                    ?>
                
                <select name="month">
                  <?php for($i = 0; $i < count($m); $i++){ ?>
                  
                    <option value="<?php echo $m[$i]; ?>" <?php if($m[$i] == date("F")) {echo "selected";} ?>><?php echo $m[$i]; ?></option>
                  
                  <?php } ?>
                </select>
                
                <select name="day">
                  <?php for($i = 1; $i < 32; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if($i == date("d")) {echo "selected";} ?>><?php echo $i; ?></option>
                  <?php endfor; ?>
                </select>
                
                <select name="year">
                  <?php for($i = date("Y"); $i < date("Y") + 5; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if($i == date("Y")) {echo "selected";} ?>><?php echo $i; ?></option>
                  <?php endfor; ?>
                </select>
                
                <select name="hour">
                    
                    <option value="time" selected>Time</option>

                    <?php for ($i = 1; $i < 13; $i++): ?>
                    
                      <?php
                    
                      /*
                        $selected1 = false;
                        $selected2 = false;
                      
                        if(date)
                        {
                          
                        }
                    */
                      ?>
                    
                      <option value="<?php echo $i; ?>:00" <?php if($i == date("g") && date("i") >0 && date("i") < 30) {echo "selected";} ?>><?php echo $i; ?>:00</option>
                      <option value="<?php echo $i; ?>:30" <?php if($i == date("g") && date("i") > 30) {echo "selected";} ?>><?php echo $i; ?>:30</option>
                    <?php endfor; ?>
                      
                </select>

                <select name="ampm">
                  <option value="am" <?php if(date("a") == "am") {echo "selected";} ?>>AM</option>
                  <option value="pm" <?php if(date("a") == "pm") {echo "selected";} ?>>PM</option>
                </select>
                
              </div>

              <div class="form-section">
                <label for="message">Message</label><br>
                <textarea name="message" id="message" rows="5" cols="30" placeholder="Message"></textarea>
              </div>
              
              <?php if($success != ""): ?>
              <div class="form-section">
                <?php echo $success; ?>
              </div>
              <?php endif; ?>
              
              <div class="form-section">
                <button class="button" name="submitMsg" type="submit">Submit</button>
                <button class="button" type="reset">Reset</button>
              </div>
            </form>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </body>
</html>