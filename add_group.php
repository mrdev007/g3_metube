<?php
session_start();

include_once "functions.php";

?>

<head> 
<title>Add Group</title>
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
$username = $_SESSION['username'];

if(isset($_POST['submit'])) {
		if($_POST['groupname'] == "") {
			$contact_error = "Please enter a Group name.";
		}
		if($_POST['topic'] == "") {
			$contact_error = "Please enter the Topic to be discussed.";
		}
		else {
			$groupname = $_POST['groupname'];
			$topic = $_POST['topic'];
			$discussion = $_POST['discussion'];
			$check = addGroup($_SESSION['username'], $groupname, $topic, $discussion);

			if($check==1) {
				$contact_error = "".$groupname." already exists";
			}
			else if($check==2){
				$contact_error = "Some other error.";
			}	
			else if($check==0){
				echo "Group created successfully";
			}
		}
}


 
?>
	<form method="post" action="<?php echo "add_group.php"; ?>">

	<table width="100%">
		<tr>
			<td  width="20%">Group Name:</td>
			<td width="80%"><input class="text"  type="text" name="groupname" maxlength="15"><br /></td>
		</tr>
		<tr>
			<td  width="20%">Topic:</td>
			<td width="80%"><input class="text"  type="text" name="topic" maxlength="15"><br /></td>
		</tr>
		<tr>
			<td  width="20%">Discussion:</td>
			<td width="80%"><input class="text"  type="text" name="discussion" maxlength="50"><br /></td>
		</tr>
        <tr>
		<td><input name="submit" type="submit" value="Submit"><br /></td>
		</tr>
	</table>
	</form>

	<a href="browse.php">Home</a>

<?php
  if(isset($contact_error))
   {  
   	echo "<h3>".$contact_error."</h3>";
	}

?>
