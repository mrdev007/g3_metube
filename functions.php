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
