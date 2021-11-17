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
