<?php
session_start();

/*
 * NOTE: Salte should not be static. Generate a salt based on the user's info somewhere, or store it in a separete DB table...
 */
define("SALT", "FH3#%FNDNndJHDJj99920))^");
define("PEPPER", "B29@!H3-039O^Fflkjfes83");
$loggedIn; //do not leave this set as true, remove this when you need to be able to log in
$email = "Not set";
$pass = "Not set";
$submittedMsg = $_POST['submitMsg'];

//process user login
if (isset($_POST['submitli']))
{
  $email = $_POST['email'];
  $pass = $_POST['password'];
  //$pass = hash("sha-512", SALT . $_POST['password'] . PEPPER);
  
  echo "<div id='test'>";
    echo "<h2>Login Info</h2>";
    echo "<p>email: $email</p>";
    echo "<p>pass: $pass</p>";
  
  echo "</div>";
}

//register the user as logged in
if (!isset($_SESSION['logged_in']))
{
  $_SESSION['logged_in'] = false;
  /* uncomment to control login form!
    $loggedIn = $_SESSION['loggedIn'];
   */
  
    $loggedIn = $_SESSION['loggedIn'];
}

//process message to be sent by logged in user
if(isset($submittedMsg))
{
  $emailAddress = $_POST['emailAddress'];
  $hour         = $_POST['hour']; 
  $ampm         = $_POST['ampm'];
  $message      = $_POST['message'];
  
  echo "<div id='test'>";
    echo "<h2>Submitted Info</h2>";
    echo "<p>email: $emailAddress</p>";
    echo "<p>hour: $hour</p>";
    echo "<p>ampm: $ampm</p>";
    echo "<p>message: $message</p>";
  
  echo "</div>";
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
      
      h1
      {
        font-size: 150%;
        font-weight: bold;
      }
      
    </style>
    
    <!-- jQuery  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    
  </head>
  <body>

    <div id="container">
      
      <?php if (!$loggedIn): ?>

        <?php require("login.php"); ?>

      <?php else: ?>

        <div class="row">

          <div>
            <form name="msg" method="post" action="">
              <div class="form-section">
                <label for="emailAddress">Email address</label><br>
                <input type="email" name="emailAddress" id="emailAddress" placeholder="Email">
              </div>

              <div class="form-section">

                <label>Choose the time to send the email</label><br>
                <select name="hour">
                    
                    <option selected>Time</option>

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