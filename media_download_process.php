<?php
session_start();
include_once "functions.php";



$username=$_SESSION['username'];
$mediaid=$_GET['id'];


$insertDownload="insert into download(downloadid,username,mediaid) values(NULL,'$username','$mediaid')";
$qryresult = mysqli_query($con, $insertDownload);

$qry = "SELECT filepath, filename FROM media WHERE mediaid='$mediaid'";
$res = mysqli_query($con, $qry);
$row = mysqli_fetch_row($res);
$file = $row[0].$row[1];

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    echo "Read";
    exit;
}
	
?>


