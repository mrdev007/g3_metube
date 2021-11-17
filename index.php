<!DOCTYPE html>
<html>
<head> 
<title>Login</title>
<link rel="stylesheet" type="text/css" href="default.css" />
</head>

<div>
    <nav>
  <a href="browse.php"><img src="img/icon_metube.png" width="85" height="40" alt="logo"></a>
  <?php
  if(!empty($_SESSION['logged_in']))
  {
    echo "<a href='logout.php'>Logout</a>
    <a href='update.php'>Profile</a>";
    }
    else {
        
        echo"<a href='index.php'>Login</a>
        <a href='registration.php' >Register</a>";
    }
  ?>
  </nav>
  </div>

  <body>
      <div class = "body">
        <h1>Welcome to MeTube</h1>
        <h3>Please login or register to continue.</h3>
        <?php
            session_start();

            include_once "functions.php";

            if(isset($_POST['submit']))
            {
                if($_POST['lusernm'] == "" || $_POST['lpass'] == "")
                {
                    $login_error = "one or more fields are missing";
                }
                else
                {
                    $res = user_pass_check($_POST['lusernm'], $_POST['lpass']);
                    if($res == 1)
                    {
                        $login_error = "User ".$_POST['lusernm']." not found.";
                    }
                    elseif($res == 2)
                    {
                        $login_error = "Incorrect Password.";
                    }
                    elseif($res == 3)
                    {
                        $login_error = "User is not registered.";
                    }
                    elseif($res == 0)
                    {
                        $_SESSION['username'] = $_POST['lusernm'];
                        $_SESSION['logged_in'] = 1;
                        header("Location: browse.php");
                        exit;
                    }
                }
            }
        ?>
        <form method="post" action="index.php">
            <label for="lusernm">Username:</label><br>
            <input type="text" id="lusernm" name="lusernm" placeholder="Enter Username"><br>
            <label for="lpass">Password:</label><br>
            <input type="password" id="lpass" name="lpass" placeholder="Enter Password">
            <input type="submit" value="Submit" name="submit">
            <input type="reset" value="reset">
            <br/>
            <p>Not a user? <a href=registration.php>Click here to register</a></p>
        </form>
        <?php
        
        if(isset($_POST['submit'])){
        if(isset($login_error))
        {
            echo "<div id=\"login_error\"><p>".$login_error."<p></div>";
        }}
        ?>
      </div>
      
  </body>