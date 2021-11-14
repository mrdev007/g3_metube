<head> 
<title>Registration</title>
<link rel="stylesheet" type="text/css" href="default.css" />
</head>

<body>
        <nav>
        <a href="browse.php"><img src="img/icon_metube.png" width="85" height="40" alt="logo"></a>
        <?php
            if(!empty($_SESSION['logged_in']))
            {
                echo "<a href='logout.php'>Logout</a>
                <a href='update.php'>Profile</a>";
            }
            else {
                
                echo"<a href='index.php';'>Login</a>
                <a href='registration.php' >Register</a>";
            }
            ?>
        </nav>
    
        <?php
        session_start();
        include_once "functions.php";

        if(isset($_POST['submit']))
        {
            if($_POST['rusernm'] == "" || $_POST['rmail'] == "" || $_POST['rpass'] == "" || $_POST['rpassc'] == "")
            {
                $login_error = "One or more fields are empty.";
            }
            else
            {
                $username = $_POST['rusernm'];
                $qry = "SELECT * from account where username='$username'";
                $result = mysqli_query($con, $qry);
                $row = mysqli_fetch_row($result);
                if($row)
                {
                    $login_error = "Username already exists.";
                }
                else
                {
                    $email = $_POST['rmail'];
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        $login_error = "Invalid email format";
                    }
                    else{
                    $pass = $_POST['rpass'];
                    $cpass  = $_POST['rpassc'];
                    if(strcmp($pass,$cpass))
                    {
                        $login_error = "Passwords do not match";
                    }
                    else
                    {
                        $id = rand(0000, 9999);
                        $username = $_POST['rusernm'];
                        $qry = "INSERT INTO account(username, password, email, id) VALUES ('$username', '$pass', '$email', '$id')";
                        $result = mysqli_query($con, $qry);
                        if($result)
                        {
                            $smsg = "User Created Successfully";
                            $_SESSION['username']=$username;
                            $_SESSION['logged_in']=1;
                            header('Location : browse.php');
                        }
                        else
                        {
                            $fmsg = "User Registration failed".mysqli_error($con);
                        }
                    }
                    }
                }
            }
        }
        ?>
        <form method = "post" action = "registration.php">
            <?php 
            if(isset($smsg))
            {
                 echo "<p>".$smsg."</p>"; 
            }
            ?> 
            <?php if(isset($fmsg)){ echo "<p>".$fmsg."</p>";}?>
            <label for="rusernm">Username:</label><br>
            <input type="text" id="rusernm" name="rusernm" placeholder="Enter Username"><br>
            <label for="rmail">Email:</label><br>
            <input type="text" id="rmail" name="rmail" placeholder="Enter Email"><br>
            <label for="rpass">Password:(max 10 characters)</label><br>
            <input type="password" id="rpass" name="rpass" placeholder="Enter Password"><br>
            <label for="rpassc"> Confirm Password:</label><br>
            <input type="password" id="rpassc" name="rpassc" placeholder="Confirm Password">
            <input type="submit" value="Submit", name="submit">
            <input type="reset" value="reset">
        </form>
        <p>Already a user?</p>
        <form action="index.php"><input name="login" type="submit" value="Login Here"></form>

        <?php
        if(isset($_POST['submit'])){
        if(isset($login_error))
        {  echo "<p>".$login_error."</p>";}}
        ?>
</body>
