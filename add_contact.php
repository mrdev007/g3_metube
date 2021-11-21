<?php
session_start();

include_once "functions.php";

?>

<head> 
<title>Add Contact</title>
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
		if($_POST['contactname'] == "") {
			$contact_error = "Please enter a contact username.";
		}
		else {
			$contactname = $_POST['contactname'];
			$relation = $_POST['relation'];
			$check = addContact($_SESSION['username'], $contactname, $relation);

			if($check == 1) {
				$contact_error = "User ".$_POST['contactname']." not found.";
			}
			elseif($check==2) {
				$contact_error = "You already have ".$contactname." as a contact.";
			}
			else if($check==3){
				$contact_error = "Some other error.";
			}	
			else if($check==4){
				$contact_error = "User blocked you, cannot add";
			}	
			else if($check==0){
				echo "Contact created successfully";
				$id=rand(0000,9999);
				$qry = "INSERT INTO conversations(conversationid,userA, userB) VALUES('$id','$username', '$contactname')";
				$res = mysqli_query($con, $qry);
				if (!$res){
					echo "error";
					echo mysqli_error($con);
				}
			}
		}
}


 
?>
	<form method="post" action="<?php echo "add_contact.php"; ?>">

	<table width="100%">
		<tr>
			<td  width="20%">Contact Username:</td>
			<td width="80%"><input class="text"  type="text" name="contactname" maxlength="15"><br /></td>
		</tr>
		<tr>
			<td  width="20%">Relation:</td>
			<td width="80%"><select name="relation">
  <option value="none">None</option>
  <option value="family">Family</option>
  <option value="friend">Friend</option>
  <option value="favorite">Favorite</option>
</select><br /></td>
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
