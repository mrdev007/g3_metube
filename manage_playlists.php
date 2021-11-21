<!DOCTYPE html>
<?php
	session_start();
	include_once "functions.php";
?>
<html>
<head>
<title>Manage Playlists</title>
</head>

<?php
	$username = $_SESSION['username'];
	if(isset($_POST['playlistname'])) {
		$playlistname = $_POST['playlistname'];

		$qry = "DELETE FROM userplaylist WHERE playlist='$playlistname' AND username='$username'";
		$res = mysqli_query($con, $qry );

		$qry = "DELETE FROM playlists WHERE playlist='$playlistname' AND username='$username'";
		$res = mysqli_query($con, $qry );
	}
	if(isset($_POST['mediaid'])) {
		$mediaid = $_POST['mediaid'];
		$qry = "DELETE FROM playlists WHERE mediaid='$mediaid' AND username='$username'";
		$res = mysqli_query($con, $qry );
		if(!$res){
			echo mysqli_error($con);
		}
	}

?>

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

<?php
	$user = $_GET['user'];

	$qry = "SELECT playlist FROM userplaylist WHERE username='$user'";
	$res = mysqli_query($con, $qry);
	$count = mysqli_num_rows($res);

	if ($count < 1){
		echo "You have no playlists.";
	}

	while($row = mysqli_fetch_row($res)){
		$playlistname = $row[0]; ?>
		<table>
			<tr>
				<th>Playlist: <?php echo $row[0]; ?></th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th>
					<?php $path = "manage_playlists.php?user=".$_GET['user']; ?>
					<form action=<?php echo $path ?> method="post">
						<input type="hidden" name="playlistname" value="<?php echo $playlistname; ?>">
						<input type="submit" value="Delete Playlist">
					</form><br>
				</th>
			</tr>
			<?php
				$qry = "SELECT media.mediaid, title FROM media INNER JOIN playlists ON media.mediaid=playlists.mediaid WHERE playlists.username='$username' AND playlists.playlist='$playlistname'";
				$titles = mysqli_query($con, $qry);
				if(!$titles){
					echo mysqli_error($con);
				}
				while($title = mysqli_fetch_row($titles)) {
					$mediaid = $title[0]; ?>
					<tr>
						<td><?php echo $title[1]; ?></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td>
							<?php $path = "manage_playlists.php?user=".$_GET['user']; ?>
							<form action=<?php echo $path ?> method="post">
								<input type="hidden" name="mediaid" value="<?php echo $mediaid; ?>">
								<input type="submit" value="Delete Media">
							</form><br>
						</td>
					</tr>
				<?php } ?>
		</table>
	<?php } ?>

</body>
</html>
