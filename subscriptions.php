<!DOCTYPE html>
<?php
	session_start();
	include_once "functions.php";
?>
<html>
<head>
<title>Subscriptions</title>
<link rel="stylesheet" href="default.css" tpe="text/css">
</head>

<body>

<nav>
        <a href="browse.php"><img src="img/icon_metube.png" width="80" height="40" alt="logo"></a>
        <?php
            if (!empty($_SESSION['logged_in']))
            {
                echo "<a href='logout.php'>Logout</a>
                <a href='update.php'>Profile</a>";
            }
            else {
                echo"<a href='index.php'>Login</a>"; 
                echo"<a href='registration.php'>Register</a>";
            }
        ?>
        <div class="search-container">
        <form action="browsefilter.php" method="post">
            <input type="text" id="searchwords"name="searchwords" placeholder="Search Keywords">
            <input type="submit" name="submit" value="Search">
        </form>
        </div>
    </nav>
<h1><?php echo $_GET['id'];?></h1>
<br/><br/>
<?php
	$user=$_GET['id'];
	$query = "select * from media where user='$user'";
	$result = mysqli_query($con, $query );
?>
<?php
	while($row = mysqli_fetch_row($result))
    { ?>
		<div class="media_box">
			<?php
				$mediaid = $row[0];
				$filename=$row[1];
				$filepath=$row[2];
				$type=$row[3];
				if(substr($type,0,5)=="image") //view image
				{
					echo "<img src='".$filepath.$filename."' height=200 width=300/>";
				}
				else //view movie
				{
			?>
					<video width="320" height="240" controls>
			<source src="<?php echo $filepath.$filename; ?>" type="video/mp4">
		</video>
				<?php } ?>
			<div><h4><a href="media.php?id=<?php echo $row[0];?>" target="_blank"><?php echo $row[4];?></a></h4></div> 
			</div>
			
<?php }?>

</div>

</body>
</html>
