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
    if($result->num_rows > 0) {
      $res = $result->fetch_assoc();
    }
    else {
      echo "Username and password combination incorrect. Try again.";
      exit();
    }
    $thisPass = hash("sha512", $res['salt'], $_POST['password']);

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
  $pass = hash("sha512", $salt, $_POST['rpassword']);
  
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
      echo 'Welcome, ' . $email . '! You are now logged in. <a href="index.php">Click here</a> to go to the main page.';
    }
    
  }
  
  mysqli_close($link);
}

//process message to be sent by logged in user
if(isset($submittedMsg))
{
  $hour             = $_POST['hour']; 
  $ampm          = $_POST['ampm'];
  $month          = $_POST['month'];
  $day               = ($_POST['day'] < 10) ? "0" . $_POST['day'] : $_POST['day'];
  $year              = $_POST['year'];
  $date             = "$month:$day:$year";
  $message      = $_POST['message'];
  $timestamp   = "$date:$hour:$ampm";
  
  $link = mysqli_connect("localhost", "root", "root", "project4");
  if(!$link)
  {
    echo "DB not connecting. Error: " . mysqli_connect_error();
    exit();
  }
 
  $error = "";
  
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
  $query = "INSERT INTO messages (username, msg, scheduled_time) VALUES ('$email', '$message', '$timestamp')"; //insert into table messages
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
    $success  = "Email stored and will send out on " . $dateTime[0] . " "  . $dateTime[1] . ", " . $dateTime[2] . " " . $dateTime[3] . ":" . $dateTime[4] . " " . $dateTime[5];
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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    
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

                <label>Choose the time to send the email</label><br>
                
                <select name="month">
                  <option value="mon" selected>Month</option>
                  <option value="January">January</option>
                  <option value="February">February</option>
                  <option value="March">March</option>
                  <option value="April">April</option>
                  <option value="May">May</option>
                  <option value="June">June</option>
                  <option value="July">July</option>
                  <option value="August">August</option>
                  <option value="September">September</option>
                  <option value="October">October</option>
                  <option value="November">November</option>
                  <option value="December">December</option>
                </select>
                
                <select name="day">
                  <option value="d" selected>Day</option>
                  <?php for($i = 1; $i < 32; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                  <?php endfor; ?>
                </select>
                
                <select name="year">
                    <option value="y" selected>Year</option>
                  <?php for($i = date("Y"); $i < date("Y") + 5; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                  <?php endfor; ?>
                </select>
                
                <select name="hour">
                    
                    <option value="time" selected>Time</option>

                    <?php for ($i = 1; $i < 13; $i++): ?>
                      <option value="<?php echo $i; ?>:00"><?php echo $i; ?>:00</option>
                      <option value="<?php echo $i; ?>:30"><?php echo $i; ?>:30</option>
                    <?php endfor; ?>
                      
                </select>

                <select name="ampm">
                  
                  <option value="am">AM</option>
                  <option value="pm">PM</option>

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