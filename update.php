<?php
session_start();
include_once "functions.php";
?>
<html>
    <head>
        <title>Update Profile</title>
        <link rel="stylesheet" type="text/css" href="default.css">
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
        $susername=$_SESSION['username'];
        $qry="SELECT * from account where username='$susername'";
        $res=mysqli_query($con, $qry);
        $row=mysqli_fetch_row($res);
        $semail=$row[3];
        $spass=$row[2];

        if(isset($_POST['update_pass']))
        {
            if($_POST['email']=="" || $_POST['old_pass']=="" || $_POST['new_pass']=="" || $_POST['c_pass']=="")
            {
                $update_error="One or more fileds are empty.";
            }
            else
            {
                $email=$_POST['email'];
                $old_pass=$_POST['old_pass'];
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $update_error="Email format is incorrect.";
                }
                else
                {
                    $new_pass=$_POST['new_pass'];
                    $c_pass=$_POST['c_pass'];
                    if($old_pass != $spass)
                    {
                        $update_error="Old password is incorrect.";
                    }
                    else
                    {
                        if($new_pass != $c_pass)
                        {
                            $update_error="New passwords does not match.";
                        }
                        else
                        {
                            $qry="UPDATE account set email='$email',password='$new_pass' where username='$susername'";
                            $res=mysqli_query($con, $qry);
                            if($res)
                            {
                                $smsg="Passwords updated sucessfully!!!!!!!!!!!";
                            }
                            else
                            {
                                $fmsg="Password update failed ".mysqli_error($con); 
                            }
                        }
                    }
                }
            }
        }
        if(isset($update_error))
        {
            echo "<h2>".$update_error."</h2>";
        } 

        if(isset($_POST['delcontact']))
        {
            $delusername=$_POST['delcontact'];
            $res=mysqli_query($con,"SELECT conversationid from conversations where (userA='$susername' AND userB='$delusername') OR (userB='$susername' AND userA='$delusername')");
            $convid_row=mysqli_fetch_row($res);
            $del_convid=(int)$convid_row[0];
            $qry = "SELECT id FROM contacts WHERE username='$susername'";
            $res = mysqli_query($con, $qry);
            $row = mysqli_fetch_row($res);
            $userid = (int)$row[0];
            $qry = "SELECT id FROM users WHERE username='$delusername'";
            $res = mysqli_query($con, $qry);
            $row = mysqli_fetch_row($res);
            $contactid = (int)$row[0];

            $qry="DELETE from conversations where conversationid='$del_convid'";
            $res=mysqli_query($con, $qry);
            if(!$res)
            {
                echo mysqli_error($con);
            }
            $qry="DELETE from messages where convid='$del_convid'";
            $res=mysqli_query($con, $qry);
            if(!$res)
            {
                echo mysqli_error($con);
            }
            $qry="DELETE from contacts where userid='$userid' and contactid='$contactid'";
            $res=mysqli_query($con, $qry);
            if(!$res)
            {
                echo mysqli_error($con);
            }
            $qry="DELETE from contacts where userid='$contactid' and contactid='$userid'";
            $res=mysqli_query($con, $qry);
            if(!$res)
            {
                echo mysqli_error($con);
            }
        } ?>
        <h1>My Profile</h1>
        <h3>User Info</h3>
        <form method="post" action="update.php">
            Username: <?php echo $_SESSION['username']; ?> <br><br>
            <label for="mail">Email:</label>
            <input type="text" id="mail" name="email" placeholder="Enter Email"/><br><br>
            <label for="old_pass">Old Password:</label>
            <input type="password" id="old_pass" name="old_pass" placeholder="Current Password"/><br><br>
            <label for="new_pass">New Password:</label>
            <input type="password" id="new_pass" name="new_pass" placeholder="New Password"/><br><br>
            <label for="c_pass">Confirm Password:</label>
            <input type="password" id="c_pass" name="c_pass" placeholder="Confirm new password"/><br><br>
            <input type="submit" value="Update Password" name="update_pass">
            <input type="reset" value="reset" /> 
        </form>

        <h3>Contacts</h3>
        <?php
        $qry="SELECT id from account where username='$susername'";
        $res=mysqli_query($con, $qry);
        $row=mysqli_fetch_row($res);
        $userid=$row[0];

        $qry="SELECT username, email, priority from account INNER JOIN contacts on account.id=contacts.contactid where contacts.userid='$userid' ORDER BY priority";
        $res=mysqli_query($con, $qry);

        if(!$res)
        {
            echo "contact failed".mysqli_error($con);
        }
        else
        { ?>
            <table>
                <tr>
                    <td>Username</td>
                    <td>Email</td>
                    <td>Relation</td>
                    <td>Message</td>
                </tr>
                <?php
                $username=$_SESSION['username'];
                while($row=mysqli_fetch_array($res, MYSQLI_NUM))
                { 
                    $conv_qry="SELECT conversationid from conversations where (userA='$username' and userB='$row[0]') or (userA='$row[0]' and userB='$username')";
                    $conv_res=mysqli_query($con, $conv_qry);
                    $conv_row=mysqli_fetch_row($conv_res);
                    $convid=$conv_row[0];
                    ?>
                    <tr>
                        <td><?php echo $row[0]; ?></td>
                        <td><?php echo $row[1]; ?></td>
                        <td><?php echo $row[2]; ?></td>
                        <td><a href="message.php?id=<?php echo $convid; ?>" target="_blank">Message</a></td> 
                    </tr>
                    <?php
                    if(!empty($_SESSION['logged_in']))
                    {
                        $qry="SELECT id from account where username='$susername'";
                        $res=mysqli_query($con, $qry);
                        $res_row=mysqli_fetch_row($res);
                        $id=$res_row[0];
                        $qry="SELECT id from account where username='$row[0]'";
                        $res=mysqli_query($con, $qry);
                        $res_row=mysqli_fetch_row($res);
                        $contact_id=$res_row[0];
                        $qry="SELECT COUNT(*) from contacts where isblock='block' and userid='$userid' and contactid='$contact_id'";
                        $favs=mysqli_query($con, $qry);
                        $favs_row=mysqli_fetch_row($favs);
                        if($favs_row[0] == 0)
                        { ?>
                            <td>
                                <form method="post" action="update.php">
                                    <input type="hidden" name="block" value="<?php echo $contact_id; ?>"/>
                                    <input type="submit" value="Block" name="block"/><br>
                                </form>
                            </td>
                        <?php } 
                        else
                        { ?>
                            <td>
                                <form method="post" action="update.php">
                                    <input type="hidden" name="unblock" value="<?php echo $contact_id; ?>"/>
                                    <input type="submit" value="Unblock" name="unblock"/><br>
                                </form>
                            </td>
                        <?php } 
                    }
                     ?>
                        <td>
                            <form method="post" action="update.php">
                                <input type="hidden" name="delcontact" value="<?php echo $row[0]; ?>"/>
                                <input type="submit" value="Delete" name="delcontact"/><br>
                            </form>
                        </td>
                    <?php } 
            } ?>
        </table>
        <p>Click <a href="add_contact.php">here</a> to add a contact by username</p>
        <?php
        if(isset($_POST['block']))
        {
            $qry="SELECT id from account where username='$susername'";
            $res=mysqli_query($con, $qry);
            $row=mysqli_fetch_row($res);
            $id=$row[0];
            $contactid=$_POST['block'];
            $qry="UPDATE contacts set isblock='block' where userid='$id' and contactid='$contactid'";
            $res=mysqli_query($con, $qry);
            echo "<meta http-equiv='refresh' content='0;url='update.php'>";
        }

        if(isset($_POST['unblock']))
        {
            $qry="SELECT id from account where username='$susername'";
            $res=mysqli_query($con, $qry);
            $row=mysqli_fetch_row($res);
            $id=$row[0];
            $contactid=$_POST['unblock'];
            $qry="UPDATE contacts set isblock='unblock' where userid='$id' and contactid='$contactid'";
            $res=mysqli_query($con, $qry);
            echo "<meta http-equiv='refresh' content='0;url='update.php'>";
        } ?>
        <h3>Friends</h3><br>
        <table>
            <tr>
                <td>Username</td>
                <td>Email</td>
                <td>Message</td>
            </tr>
            <?php
            $qry="SELECT id from account where username='$susername'";
            $res=mysqli_query($con, $qry);
            $row=mysqli_fetch_row($res);
            $userid=$row[0];
            $qry="SELECT username,email from account INNER JOIN contacts on account.id=contacts.userid where contacts.userid='$userid' and contacts.priority='friends'";
            $res=mysqli_query($con, $qry);
            while($row=mysqli_fetch_array($res, MYSQLI_NUM))
            {
                $conv_qry="SELECT conversationid  from conversations where (userA='$susername' and userB='$row[0]' or (userA='$row[0]' and userB='$susername')";
                $conv_res=mysqli_query($con, $conv_qry);
                $conv_row=mysqli_fetch_row($conv_res);
                $conv_id=$conv_row[0]; ?>
                <tr>
                    <td><?php echo $row[0]; ?></td>
                    <td><?php echo $row[1]; ?></td>
                    <td><a href="message.php?id='<?php echo $conv_id; ?>'" target="_blank">Message</a></td>
                    <td>
                        <form method="post" action="update.php">
                            <input type="hidden" name="del_contact" value="<?php echo $row[0]; ?>"/>
                            <input type="submit" value="Delete" name="del_contact"/>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <h3>My media</h3>
        <table width="50%">
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Category:
                    <form method="post" action="update.php">
                        <select name="type" type="text">
                            <option value="all" selected="selected">ALL</option>
                            <option value="image" >Images</option>
                            <option value="video" >Videos</option>
                            <option value="audio" >Audio</option>
                        </select>
                        <input type="submit" value="Sort" name="sort"/>
                    </form>
                </th>
            </tr>
            <?php
            $catqry="";
            if(isset($_POST['sort']))
            {
                $type=$_POST['type'];
                if($type=='all')
                {
                    $catqry="AND media.category in ('video','audio','image')";
                }
                elseif($type=='image')
                {
                    $catqry="AND media.category='image'";
                }
                elseif($type=='video')
                {
                    $catqry="AND media.category='video'";
                }
                elseif($type=='audio')
                {
                    $catqry="AND media.category='audio'";
                }
            }
            $qry="SELECT * from media INNER JOIN upload on media.mediaid=upload.mediaid INNER JOIN account on upload.username=account.username where account.username='$susername' $catqry";
            $res=mysqli_query($con, $qry);
            if(!$res)
            {
                die("Could not query the media table. ".mysqli_error($con));
            }
            while($row=mysqli_fetch_array($res, MYSQLI_NUM))
            { ?>
                <tr valign="top">
                    <td><h3><a href="media.php?id='<?php echo $row[0]; ?>'" target="_blank"><?php echo $row[4]; ?></a></h3> </td>
                    <td><?php echo $row[5]; ?></td>
                    <td><?php echo $row[6]; ?></td>
                </tr>
           <?php } ?>
        </table>

        <h3>Groups</h3>
        <table width="50%">
            <tr>
                <th>Group Name</th>
            </tr>
            <tr>
                    <?php
                    $qry="SELECT groupname from groups";
                    $res=mysqli_query($con, $qry);
                    $groupname=mysqli_fetch_row($res);
                    if($groupname!=NULL){
                    echo "<td>";
                    $qry="SELECT username from group_messages where groupname='$groupname[0]' and username='$susername'";
                    $res=mysqli_query($con, $qry);
                    $row=mysqli_fetch_row($res);
                    if($row[0]==$susername)
                    {
                        $href="groups.php?id=".$groupname[0];
                    }
                    else
                    {
                        $href="update.php";
                    } ?>
                    <a href="<?php echo $href; ?>" target="_blank"><?php echo $groupname[0]; ?></a>
                </td>
                <?php
                echo $groupname[0];
                $qry="SELECT username from group_messages where groupname='$groupname[0]' and username='$susername'";
                $res=mysqli_query($con, $qry);
                $usrname=mysqli_fetch_row($res);
                if($usrname[0]==$susername)
                { ?>
                    <td>
                        <form method="post" action="update.php">
                            <input type="hidden" name="leave1" value="<?php echo $groupname[0]; ?>"/>
                            <input type="submit" value="Leave" name="leave"/>
                        </form> 
                    </td>
                <?php }
                else
                { ?>
                    <td>
                        <form method="post" action="update.php">
                            <input type="hidden" name="join1" value="<?php echo $groupname[0]; ?>">
                            <input type="submit" name="join" value="Join"/>
                        </form><br>
                    </td>
                <?php } }?>
            </tr>
            <?php
                echo "<p> Click <a href='add_group.php'>here</a> to create a new group.</p>";
            ?>
        </table>
        <form action="browse.php">
            <input type="submit" name="home" value="Cancel"/>
        </form>
    </body>
</html>
<?php
if(isset($_POST['join']))
{
    $groupname=$_POST['join1'];
    $qry="INSERT into group_messages(groupname, username) values('$groupname','$susername')";
    $res=mysqli_query($con, $qry);
    if(!$res)
    {
        echo "join failed ".mysqli_error($con);
    } ?>
    <meta http-equiv="refresh" content="0;url=update.php">
<?php }

if(isset($_POST['leave']))
{
    $groupname=$_POST['leave1'];
    $qry="DELETE from group_messages where groupname='$groupname' and username='$susername'";
    $res=mysqli_query($con, $qry);
    if(!$res)
    {
        echo "leave failed. ".mysqli_error($con);
    }
}
?>