<!DOCTYPE html>
<?php
	session_start();
	include_once "functions.php";
?>
<html>
<head>
<title>Word Cloud</title>
<link rel="stylesheet" type="text/css" href="default.css" />
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
        <div class="search-container">
        <form action="browsefilter.php" method="post">
            <input type="text" id="searchwords"name="searchwords" placeholder="Search keyword">
            <input type="submit" name="submit" value="Search">
        </form>
        </div>
    </nav>

    <?php
      $values=[];
      $query = "SELECT distinct keyword,count from keywords";
		$result = mysqli_query($con, $query);
      $total = 0;
      while ($keyword = mysqli_fetch_array($result, MYSQLI_NUM)) {
        $word = $keyword[0];
        $count = $keyword[1];
        $new=array("$word"=>"$count");
        $values=array_merge($values,$new);
        $total += $count;
      }
      
      foreach($values as $word=>$count1)
      { ?>
          <div style="font-size: <?php  
          $per=($count1/$total)*100; 
          if($per>=90 && $per<=100){ echo "150px; color:blue;";}
          elseif($per>=80 && $per<90){ echo "135px; color:red;";}
          elseif($per>=70 && $per<80) {echo "105px; color:green;";}
          elseif($per>=60 && $per<70) {echo "90px; color:maroon;";}
          elseif($per>=50 && $per<60) {echo "75px; color:purple;";}
          elseif($per>=40 && $per>50) {echo "60px; color:lime;";}
          elseif($per>=30 && $per<40) {echo "45px; color:navy;";}
          elseif($per>=20 && $per<30) {echo "30px; color:aqua;";}
          else{ echo "15px; color:brown;";} ?>"><?php echo $word; ?> </div>
          <?php
      }
      ?>

</body>