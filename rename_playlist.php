<?php
session_start();
include_once "functions.php";
?>
<html>
    <head>
        <title>Rename playlist</title>
    </head>
    <body>
        <nav>
            <a href="browse.php"><img src="img/icon_metube.png" width="85" height="40" alt="logo"></a>
            <?php
            $old_name=$_GET['id'];
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
        <?php
        if(isset($_POST['submit']))
        {
            $newname=$_POST['new_name'];
            
            $username=$_SESSION['username'];
            $qry="SELECT COUNT(*) from userplaylist where playlist='$newname' and username='$username'";
            $res=mysqli_query($con, $qry);
            $row=mysqli_fetch_row($res);
            if($row[0]==0)
            {
                $qry="UPDATE userplaylist set playlist='$newname' where playlist='$old_name' and username='$username'";
                $res=mysqli_query($con, $qry);
                if(!$res)
                {
                    echo "the rename failed ".mysqli_error($con);
                } ?>
                <meta http-equiv="refresh" content="0;url=manage_playlists.php?user=<?php echo $username; ?>"><?php
            }
            
        } ?>
        <br><br>
        <form method="post" action="rename_playlist.php?id=<?php echo $old_name; ?>">
            <input type="text" value=<?php echo $old_name; ?>>
            <input type="text" name="new_name" placeholder="New Playlist">
            <input type="submit" value="Submit" name="submit">
        </form>
    </body>
</html>