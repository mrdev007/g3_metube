<?php
session_start();
include_once "functions.php";

$username=$_SESSION['username'];
$path='uploads/'.$username.'/';

if(!file_exists($path))
{
    mkdir($path);
    chmod($path, 0755);
}

if($_POST['title']==NULL)
{
    $result=8;
}
else
{
    if($_FILES["file"]["error"] > 0)
    {
        $result=$_FILES["file"]["error"];
    }
    else
    {
        $file=$path.urlencode($_FILES["file"]["name"]);
        if(file_exists($file))
        {
            $result="5";
        }
        else
        {
            if(is_uploaded_file($_FILES["file"]["tmp_name"]))
            {
                if(!move_uploaded_file($_FILES["file"]["tmp_name"], $file))
                {
                    $result="6";
                }
                else
                {
                    chmod($file, 0644);
                    date_default_timezone_set("America/New_york");
                    $time=date("Y-m-d h:i:sa");
                    $filename=urlencode($_FILES["file"]["name"]);
                    $filepath=$path;
                    $size=filesize($path.$filename);
                    $ext=end(explode('.', $filename));
                    $qry="INSERT into media(mediaid, filename, filepath, type, title, description, category, user, share, time, size, allow_desc, allow_rating) values
                    (NULL, '$filename', '$filepath', '".$_FILES["file"]["type"]."', '".$_POST["title"]."', '".$_POST["description"]."', '".$_POST["category"]."', '$username', '".$_POST["share"]."', '$time', '$size', '".$_POST["discussion"]."', '".$_POST["rating"]."')";
                    $qry_res=mysqli_query($con, $qry) or die("Insert into Media error in media upload process ".mysqli_error($con));
                    $result=0;

                    $mediaid=mysqli_insert_id($con);

                    $upload_qry="INSERT into upload(uploadid, username, mediaid) values(NULL,'$username','$mediaid')";
                    $qry_res=mysqli_query($con, $upload_qry) or die("Insert into upload failed in media upload process");

                    $keywrds=$_POST["keywords"];
                    $keyarr=array_map('trim', explode(',', $keywrds));
                    $key_count=1;
                    foreach($keyarr as $val)
                    {
                        $key_qry=mysqli_query($con, "INSERT into keywords(mediaid, keyword, count) values('$mediaid','$val','$key_count')");
                    }
                    $qry="INSERT into keywords(mediaid,keyword,count) values('$mediaid','$time','$count')";
                    $res=mysqli_query($con, $qry) or die("Insert into keywords error 1 in media upload process".mysqli_error($con));
                    $qry="INSERT into keywords(mediaid,keyword,count) values('$mediaid','$size','$count')";
                    $res=mysqli_query($con, $qry) or die("Insert into keywords error 2 in media upload process".mysqli_error($con));
                    $qry="INSERT into keywords(mediaid,keyword,count) values('$mediaid','$ext','$count')";
                    $res=mysqli_query($con, $qry) or die("Insert into keywords error 3 in media upload process".mysqli_error($con));
                }
            }
            else
            {
                $result="7";
            }
        }
    }
}
?>
<meta http-equiv="refresh" content="0;url=browse.php?result=<?php echo $result ; ?>">