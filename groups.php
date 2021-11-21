<!DOCTYPE html>
<?php
	session_start();
	include_once "functions.php";
?>	
<html>
<head>
<title>Groups</title>
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
	if(isset($_POST['submit'])){
		$username = $_SESSION['username'];
		$groupname = $_GET["id"];
		$msg = $_POST['message'];
		$qry = "INSERT INTO group_messages(groupname, username, message) VALUES ('$groupname', '$username', '$msg')";
		$res = mysqli_query($con, $qry);

		if($res){
			$smsg = "Message Created Successfully";
			$msgpath='Location: groups.php?id='.$_GET["id"];
			header($msgpath);
		}
		else {
			$fmsg = "Message Failed".mysqli_error($con);
		}
	}
?>
<?php
	$groupname = $_GET["id"];
    echo $groupname;
	$qry = "SELECT * FROM group_messages WHERE groupname='$groupname'";
	$res = mysqli_query($con, $qry);
	$qry = "SELECT topic FROM groups WHERE groupname='$groupname'";
	$r = mysqli_query($con, $qry);
	$r_row = mysqli_fetch_row($r);
?>
<h1>Topic:<?php echo $r_row[0]?></h1>
<h4>Messages</h4>
<table>
	<tr>
		<th>Username</th>
		<th>Message</th>
	</tr>

	<?php
		while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
			
	?>
		<tr>
			<td><?php echo $row[1] ?></td>
			<td><?php echo $row[2] ?></td>
		</tr>
		<?php } ?>
		<?php 
			$msgpath="groups.php?id=".$_GET["id"]; ?> 
				<form method="POST" action=<?php echo $msgpath ?>>
					<tr>
						<td></td>
  						<td><input name="message" type="text" placeholder="New message (max 200 characters)..." maxlength="200"><br>
  							<input name="submit" type="submit" value="Post"></td>
					</tr>
				</form>
</table>
<?php
if(isset($fmsg)) echo $fmsg;
if(isset($smsg)) echo $smsg; ?>
