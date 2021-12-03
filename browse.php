<!DOCTYPE html>
<?php
session_start();
include_once "functions.php";
?>
<html>
<head>
    <meta charset="utf-8" />
    <title>Metube|Homepage</title>
    <link rel="stylesheet" type="text/css" href="default.css" />
    <script>
    <?php
    if(! empty($_SESSION['logged_in']))
    {
    if(isset($_REQUEST['result']) && $_REQUEST['result']!=0)
	{ ?>
        alert("<?php echo "Upload failed ".upload_error($_REQUEST['result']); ?>");
	<?php }} ?>
</script>
<script type="text/javascript">
    function saveDownload(id)
    {
        $.post("media_download_process.php", 
        {
            id: id,
        },
        function(message){});
    }
</script>
</head>

<body>
    <nav>
        <a href="browse.php"><img src="img/icon_metube.png" width="80" height="40" alt="logo"></a>
        <?php
            if (!empty($_SESSION['logged_in']))
            {
                echo "<a href='logout.php'>Logout</a>
                <a href='update.php'>Profile</a>";
        ?>
        <a href="wordcloud.php">Word Cloud</a>
        <a href='media_upload.php'>Upload File</a>
        <div class="dropdown">
            <button class="dropbtn">Subscriptions</button>
            <div class="dropdown-content">
                <?php
                    $username=$_SESSION['username'];
                    $qry="select owner from subscribe where username='$username'";
                    $res=mysqli_query($con, $qry);
                    while($row=mysqli_fetch_row($res))
                    {
                        echo "<a href=\"subscriptions.php?id=".$row[0]."\">".$row[0]."</a>";
                    }
                ?>
            </div>
        </div>
        <?php }
            else {
                echo"<a href='index.php'>Login</a>"; 
                echo"<a href='registration.php'>Register</a>";
            } ?>
        <div class="search-container">
        <form action="browsefilter.php" method="post">
            <input type="text" id="searchwords"name="searchwords" placeholder="Search Keywords">
            <input type="submit" name="submit" value="Search">
        </form>
        </div>
    </nav>
    <h1>Browse<h1>
        <?php
        if(! empty($_SESSION['logged_in']))
        {
            $username=$_SESSION['username'];
            if(isset($_POST['delchannel']))
            {
                $cname=$_POST['delchannel'];
                //echo "the user name is ".$username." and the channel name is ".$cname;
                $qry="DELETE from channels where user='$username' and channel='$cname'";
                $result=mysqli_query($con, $qry);
                if(!$result)
                {
                    echo mysqli_error($con);
                }
            }
        ?>
    <h3>Add new playlist</h3>
    <form method="post" action="browse.php">
        <input name="newplaylist" type="text" placeholder="new playlist" maxlength="25">
        <input type="submit" value="Submit" name="plysubmit">
    </form>
    <h3><a href="manage_playlists.php?user=<?php echo $username; ?>" target="_blank">Manage Playlists</a></h3>
    <h3>Add new Channel</h3>
    <form method="post" action="browse.php">
        <?php
        $qry="select username from account where username != '$username' and username not in (select channel from channels where user='$username')";
        $chnl_res=mysqli_query($con, $qry);
        if(!$chnl_res)
        {
            echo mysqli_error($con);
        } 
        ?>
        <select name="newchannel">
            <?php
            while($chnl_row = mysqli_fetch_row($chnl_res))
            { ?>
               <option value="<?php echo $chnl_row[0]; ?>"><?php echo $chnl_row[0]; ?> </option><br>; 
            <?php } ?>
        </select>
        <input type="submit" value="Submit">
    </form>
    <h3>My Channels</h3>
    <?php
    $qry="SELECT channel from channels where user='$username'";
    $result=mysqli_query($con, $qry); ?>
    <table>
    <?php
    while($chnl_row=mysqli_fetch_row($result))
    { ?>
        <tr>
            <td><?php echo $chnl_row[0]; ?></td>
            <td><form method="post" action="browse.php">
                <input type="hidden" name="delchannel" value="<?php echo $chnl_row[0]; ?>">
                <input type="submit" value="Delete" >
                </form>
            </td>
        </tr>
    <?php } ?>
    </table>
    <?php }
    else
    {
        echo "Please login to upload the media";
    }
    if(isset($_POST['channel']))
    {
        $channel=$_POST['channel'];
        if($channel == 'all')
        {
            $chnlqry="SELECT mediaid from media";
        }
        elseif($channel == "mine")
        {
            $chnlqry="SELECT mediaid from media where user='$username'";
        }
        else
        {
            $chnlqry="SELECT mediaid from media where user='$channel'";
        }
    }
    else
    {
        $chnlqry="SELECT mediaid from media";
    }

    if(isset($_POST['type']))
    {
        $type=$_POST['type'];
        if($type == "all")
        {
            $typeqry="SELECT mediaid from media";
        }
        elseif($type == "image")
        {
            $typeqry="SELECT mediaid from media where category='image'";
        }
        elseif($type == "video")
        {
            $typeqry="SELECT mediaid from media where category='video'";
        }
        elseif($type == "audio")
        {
            $typeqry="SELECT mediaid from media where category='audio'";
        }
    }
    else
    {
        $typeqry="SELECT mediaid from media";
    }
    

    if(isset($_POST['playlist']))
    {
        $playlist=$_POST['playlist'];
        if($playlist == "all")
        {
            $plylstqry="SELECT mediaid from media";
        }
        else
        {
            $plylstqry="SELECT media.mediaid from media INNER JOIN playlists on media.mediaid=playlists.mediaid where playlists.playlist='$playlist' and username='$username'";
        }
    }
    else
    {
        $plylstqry="SELECT mediaid from media";
    }
    
    $bigq="SELECT media.mediaid from media where media.mediaid in ($chnlqry) and media.mediaid in ($typeqry) and media.mediaid in ($plylstqry)";
    $allq="SELECT * from media where media.mediaid in ($bigq)";

    if(isset($_POST['order']))
    {
        $order=$_POST['order'];
        if($order=="recent")
        {
            $allq="SELECT * from media where media.mediaid in ($bigq) ORDER BY time DESC";
        }
        if($order=="name")
        {
            $allq="SELECT * from media where media.mediaid in ($bigq) ORDER BY filename";
        }
        if($order=="size")
        {
            $allq="SELECT * from media where media.mediaid in ($bigq) ORDER BY size";
        }
        if($order=="viewed")
        {
            $allq="SELECT * from media where media.mediaid in ($bigq) ORDER BY views DESC";
        }
    }

    $result=mysqli_query($con, $allq);
    if(!$result)
    {
        echo mysqli_error($con);
    }


    if(isset($_POST['newchannel']))
    {
        $newchnl=$_POST['newchannel'];
        $qry="INSERT into channels(user, channel) values('$username','$newchnl')";
        $chnl_res=mysqli_query($con, $qry);
        if(!$chnl_res)
        {
            echo mysqli_error($con);
        }
        echo "<meta http-equiv=\"refresh\" content=\"0;url=browse.php\">";
    }


    if(isset($_POST['newplaylist']))
    {
        $newplaylist=$_POST['newplaylist'];
        $qry="SELECT playlist from userplaylist where username='$username' and playlist='$newplaylist'";
        $playlist_res=mysqli_query($con, $qry);
        $row=mysqli_fetch_row($playlist_res);
        if(!$row)
        {
            $qry="INSERT into userplaylist(playlist, username) values('$newplaylist','$username')";
            $new_playlst_res=mysqli_query($con, $qry);
        }
        else
        {
            echo 'You already have this playlist';
        }
    }
    ?>
    
    <h3>Filters<h3>
        <table>
            <tr>
                <th><h4>Category</h4></th>
                <?php if(!empty($_SESSION['logged_in']))
                { ?>
                    <th><h4>Playlist</h4></th>
                    <th><h4>Channel</h4></th> 
                    <th><h4>Order By</h4></th><?php } ?>
                    <th></th>
            </tr>
            <tr>
                <td>
                    <form method="post" action="browse.php">
                        <select name="type" type="type">
                            <option value="all" selected="selected">All</option>
                            <option value="image">Images</option>
                            <option value="video">Videos</option>
                            <option value="audio">Audio</option>
                        </select>
                </td>
                <?php
                if(!empty($_SESSION['logged_in']))
                { ?>
                <td>
                    <form method="post" action="browse.php">
                        <?php
                        $qry="SELECT * from userplaylist where username='$username'";
                        $playlist_res=mysqli_query($con, $qry); ?>
                        <select name="playlist">
                            <option value="all" selected="selected">ALL</option>
                            <option value="favourite">Favourites</option>
                            <?php
                            while($playlst_row=mysqli_fetch_row($playlist_res))
                            { ?>
                                <option value="<?php echo $playlst_row[0]; ?>"><?php echo $playlst_row[0]; ?> </option><br>
                            <?php } ?>
                        </select>
                </td>
                <td>
                    <form method="post" action="browse.php">
                        <?php
                        $qry="SELECT channel from channels where user='$username'";
                        $chnl_res=mysqli_query($con, $qry); ?>
                        <select name="channel">
                            <option value="all" selected="selected">Any</option>
                            <option value="mine">My Channel</option>
                            <?php
                            while($chnl_row=mysqli_fetch_row($chnl_res))
                            { ?>
                                <option value="<?php echo $chnl_row[0]; ?>"><?php echo $chnl_row[0]; ?> </option><br>
                            <?php } ?>
                        </select>
                </td>
                <td>
                    <form method="post" action="browse.php">
                        <select name="order" type="text">
                            <option value="recent" selected="selected">Most Recent</option>
                            <option value="name">Name</option>
                            <option value="size">Size</option>
                            <option value="viewed">Most viewed</option>
                        </select>
                </td>
                <?php } ?>
                <td>
                    <input type="submit" value="Submit" name="options">
                    </form>
                </td>
            </tr>
        </table>
        <div class="allmedia">
            <?php
            while($result_row=mysqli_fetch_row($result)) //print the results 
            {
                if(empty($_SESSION['logged_in']))
                {
                    $username="NULL";
                }
                $qry="SELECT id from account INNER JOIN media on account.username=media.user";
                $res=mysqli_query($con, $qry);
                $res_row=mysqli_fetch_row($res);
                $id=$res_row[0];
                $qry="SELECT id from account where username='$username'";
                $res=mysqli_query($con, $qry);
                $res_row=mysqli_fetch_row($res);
                $contactid=$res_row[0];
                $qry="SELECT isblock from contacts where userid='$id' and contactid='$contactid'";
                $res=mysqli_query($con, $qry);
                $res_row=mysqli_fetch_row($res);
                //$isblock=$res_row[0];
                if($res_row != NULL && $res_row[0]=='block')
                {
                    continue;
                }
                $qry="SELECT user from media where mediaid='$result_row[0]'";
                $user_share_res=mysqli_query($con, $qry);
                $user_share_row=mysqli_fetch_row($user_share_res);
                if(($result_row[9]=="me") && ($user_share_row[0] != $username))
                {
                    continue;
                }
                $qry="SELECT priority from account INNER JOIN contacts on account.id=contacts.contactid where account.username='$username'";
                $user_share_res=mysqli_query($con, $qry);
                $user_share_row1=mysqli_fetch_row($user_share_res);
                if(($result_row[9]=="friends") && ($user_share_row1[0] != "friend"))
                {
                    if($user_share_row[0] != $username)
                    {
                        continue;
                    }
                }
                if(($result_row[9]=="family") && ($user_share_row1[0] != "family"))
                {
                    if($user_share_row[0] != $username)
                    {
                        continue;
                    }
                }
                if(($result_row[9]=="favourites") && ($user_share_row1[0] !="favourite"))
                {
                    if($user_share_row[0] != $username)
                    {
                        continue;
                    }
                } ?>
                <div class="mediabox">
                    <?php
                    $mediaid=$result_row[0];
                    $filename=$result_row[1];
                    $filepath=$result_row[2];
                    $type=$result_row[3];
                    if(substr($type,0,5)=="image")
                    {
                        echo "<img src='".$filepath.$filename."' height=200 width=300/>";
                    }
                    else
                    { ?>
                        <video width="320" height="240" controls>
                            <source src="<?php echo $filepath.$filename; ?>" type="video/mp4">
                        </video>
                    <?php } ?>
                    <h4><a href="media.php?id=<?php echo $result_row[0]; ?>" target="_blank"><?php echo $result_row[4]; ?></a></h4>

                    <form method="post" action="browse.php">
                        Rating:
                        <?php
                        $qry="SELECT AVG(rating) from rating_data where mediaid='$result_row[0]'";
                        $rate_res=mysqli_query($con, $qry);
                        $rate_row=mysqli_fetch_row($rate_res);
                        if($rate_row[0]==NULL)
                        {
                            echo "0";
                        }
                        else
                        {
                            echo $rate_row[0];
                        }
                        ?>
                    </form>
                    Views:
                    <?php
                    $qry="SELECT views from media where mediaid='$result_row[0]'";
                    $rate_res=mysqli_query($con, $qry);
                    $rate_row=mysqli_fetch_row($rate_res);
                    if($rate_row[0]==NULL)
                    {
                        echo "0";
                    }
                    else
                    {
                        echo $rate_row[0];
                    }
                    ?>

                </div>
            <?php } ?>
        </div>
</body>
</html>