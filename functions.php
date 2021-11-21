<?php
include_once ("mysqlclass.php");
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $database) ;
if ($con->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function user_pass_check($username, $pass)
{
  global $con;
  $qry = "select * from account where username='$username'";
  $result = mysqli_query($con, $qry);
  
  if(!$result)
  {
    die("user_pass_check() failed.");
  }
  else
  {
    $row = mysqli_fetch_row($result);
    if(!$row)
    {
      return 3;//no user exists
    }
    elseif(strcmp($row[2], $pass))
    {
      return 2; //wrong pass
    }
    else
    {
      return 0; //passed
    }
  }
}

function upload_error($result)
{
	//view erorr description in http://us2.php.net/manual/en/features.file-upload.errors.php
	switch ($result){
	case 1:
		return "UPLOAD_ERR_INI_SIZE";
	case 2:
		return "UPLOAD_ERR_FORM_SIZE";
	case 3:
		return "UPLOAD_ERR_PARTIAL";
	case 4:
		return "UPLOAD_ERR_NO_FILE";
	case 5:
		return "File has already been uploaded";
	case 6:
		return  "Failed to move file from temporary directory";
	case 7:
		return  "Upload file failed";
	case 8:
		return 	"Title should not be empty";
	}
}

function addContact($username, $contactname, $relation)
{
	//You can write your own functions here.
	global $con;

	$query = "SELECT id FROM account WHERE username='$username'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$userid = $row[0];

	$query = "SELECT * FROM account WHERE username='$contactname'";
	$result = mysqli_query($con, $query );
	if (!$result)
	{
	   die ("addContact() failed. Could not query the database: <br />". mysqli_error($con));
	}
	$row = mysqli_fetch_row($result);
	if(!$row) 
		return 1; // no user exists
	$query = "SELECT id FROM account WHERE username='$contactname'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$contactid = $row[0];

	$query = "SELECT * FROM contacts WHERE userid='$userid' and contactid='$contactid'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);

	if($row)
		return 2; // already a contact
	$query = "SELECT isblock FROM contacts WHERE userid='$contactid' and contactid='$userid'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	if($row[0]=="block")
		return 4;
	$query = "INSERT INTO contacts(userid, contactid, priority) VALUES ('$userid', '$contactid', '$relation')";
	$result = mysqli_query($con, $query);
	if(!$result)
  {
		return 3;
	}
	else
  {
		return 0;
	}
}

function addGroup($username, $groupname, $topic, $discussion)
{
	global $con;

	$query = "SELECT * FROM groups WHERE groupname='$groupname'";
	$result = mysqli_query($con, $query );
	$row = mysqli_fetch_row($result);
	if ($row)
	{
	   return 1;//group exists
	}
	$query = "INSERT INTO groups (groupname, topic, discussion) VALUES('$groupname', '$topic', '$discussion')";
	$result = mysqli_query($con, $query);
	if(!$result){
		echo mysqli_error($con);
		return 2;
	}
	else {
		$query = "INSERT INTO group_messages(groupname, username) VALUES ('$groupname', '$username')";
		$result = mysqli_query($con, $query);
		if(!$result){
		return 2;
		}
		else
		{
		return 0;
		}
	}
}
?>