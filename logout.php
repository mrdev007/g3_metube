<?php
session_start();
?>
<html>
    <head>
        <title>Logout</title>
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
                
                echo"<a href='index.php'>Login</a>
                <a href='registration.php' >Register</a>";
            }
        ?>
        </nav>
    </body>
    <?php
    session_destroy();
    echo '<p>You have been logged out. <a href="index.php">Login</a><p>';
    echo '<p>Redirect to <a href="browse.php">Home Page</a></p>'; ?>
</html>