<?php
session_start(); ?>
<html>
    <head>
        <title>Media Upload</title>
        <link rel="stylesheet" type="text/css" href="default.css"/>
    </head>

    <body>
        <nav>
            <a href="browse.php"><img src="img/icon_metube.png" width="85" height="40" alt="logo"></a>
        </nav>
        <form method="post" action="media_upload_prc.php" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
            <label for="file">Add Media <label style="color:#663399"><em>(Each file limit 10MB):</em></label></label>
            <input type="file" name="file" id="file"/><br><br>
            Title:<input type="text" name="title" maxlength="20"/><br><br>
            <label for="description">Description:</label>
            <input type="text" name="description" id="description"/><br><br>
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option value="image">Image</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
            </select><br><br>
            <label for="share">Sharing Mode:</label>
            <select name="share" id="share">
                <option value="public">Public</option>
                <option value="me">Only me</option>
                <option value="family">Family</option>
                <option value="friends">Friends</option>
                <option value="favourites">Favourites</option>
            </select> <br><br>
            <label for="discussion">Allow Discussion:</label>
            <select name="discussion" id="discussion">
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select><br><br>
            <label for="rating">Allow Rating:</label>
            <select name="rating" id="rating">
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select><br><br>
            <label for="keywords">Keywords:</label>
            <textarea name="keywords" rows="5" cols="30" placeholder="Enter the Keywords seperated by commas (,)."></textarea><br>
            <br><br><input type="submit" value="Upload" name="submit"/> 
        </form>
    </body>
</html>