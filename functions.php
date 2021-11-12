<?php
include_once ("mysqlclass.php");
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $database) ;
if ($con->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
