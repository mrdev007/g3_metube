<!DOCTYPE html>
<?php
	session_start();
	include_once "functions.php";
?>	
<html>
<head>
<title>Message</title>
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
<?php 
	$username=$_SESSION['username'];
	$qry="SELECT id from account where username='$username'";
	$res=mysqli_query($con, $qry);
	$row=mysqli_fetch_row($res);
	$id=$row[0];
	$qry="SELECT * from contacts where (userid='$id' and isblock='block') or (contactid='$id' and isblock='block')";
	$res=mysqli_query($con,$qry);
	$row=mysqli_fetch_row($res);
	if($row[0]==NULL){
	if(isset($_POST['submit'])){
		$username = $_SESSION['username'];
		$convid = $_GET['id'];
		$msg = $_POST['message'];
		$qry = "INSERT INTO messages(convid, sender, message) VALUES ('$convid', '$username', '$msg')";
		$res = mysqli_query($con, $qry);

		if($res){
			$smsg = "Message Created Successfully";
			$msgpath='Location: message.php?id='.$_GET["id"];
			header($msgpath);
		}
		else {
			$fmsg = "Message Failed".mysqli_error($con);
		}
	} }
	else
	{
		echo "<h3 style=\"color:red;\">Sorry cannot send a message as this user is blocked.</h3>";
	}
?>
<?php
	$convid = $_GET['id']; 
	$qry = "SELECT userA, userB FROM conversations WHERE conversationid='$convid'";
	$users_result = mysqli_query($con, $qry);
	$user_row = mysqli_fetch_row($users_result);
	$userA = $user_row[0];
	$userB = $user_row[1];
	$qry = "SELECT * FROM messages WHERE convid='".$_GET['id']."'"."ORDER BY timesent";
	$res = mysqli_query($con, $qry);
?>

<h4>Messages between <?php echo $userA." and ".$userB; ?></h4>
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
			$msgpath="message.php?id=".$_GET["id"]; ?> 
				<form method="POST" action=<?php echo $msgpath ?>>
					<tr>
						<td></td>
  						<td><input name="message" type="text" placeholder="New message (max 200 characters)..." maxlength="200"><br>
  							<input name="submit" type="submit" value="Post"></td>
					</tr>
				</form>
</table>
<?php 
if(isset($smsg))
{
	echo $smsg;
}
if(isset($fmsg))
{
	echo $fmsg;
}