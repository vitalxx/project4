<?php
session_start();

define("SALT", "FH3#%FNDNndJHDJj99920))^");
define("PEPPER", "B29@!H3-039O^Fflkjfes83");
$loggedIn = true; //do not leave this set as true, remove this when you need to be able to log in
$email = "Not set";
$pass = "Not set";

if (!isset($_SESSION['logged_in']))
{
  $_SESSION['logged_in'] = false;
  /* uncomment to control login form!
    $loggedIn = $_SESSION['loggedIn'];
   */
}

if (isset($_POST['submitli']))
{
  //process user login
  $email = $_POST['email'];
  $pass = md5(SALT . $_POST['password'] . PEPPER);
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
      
      .form-section
      {
        margin-bottom: 15px;
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
            <form>
              <div class="form-section">
                <label for="emailAddress">Email address</label><br>
                <input type="email" name="emailAddress" class="form-control" id="emailAddress" placeholder="Email">
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
                  
                  <option>AM</option>
                  <option>PM</option>

                </select>
              </div>

              <label for="message">Message</label><br>
              <textarea name="message" id="message" rows="5" cols="30" placeholder="Message"></textarea>

              <div class="form-section">
                <button type="submit">Submit</button>
                <button type="reset">Reset</button>
              </div>
            </form>
          </div>

        </div>

      <?php endif; ?>
    </div>

  </body>
</html>