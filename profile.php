<?php

  // profile.php
  // Copyright (C) 2015 jSoft Apps, All Rights Reserved
  // Licensed under the CC-BY-NC-SA version 4.0
  // This file contains the code to display team scouting data.
  // This file also includes comments and team robot pictures

    // First we execute our common code to connection to the database and start the session
    require("includes/common.php");
    
    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user']))
    {
        // If they are not, we redirect them to the login page.
        header("Location: login.php");
        
        // Remember that this die statement is absolutely critical.  Without it,
        // people can view your members-only content without logging in.
        die("Redirecting to login.php");
    }
    
    $teamid = ($_GET['teamid']);

    
    // Everything below this point in the file is secured by the login system
    
    // We can display the user's username to them by reading it from the session array.  Remember that because
    // a username is user submitted content we must use htmlentities on it before displaying it to the user.
    
    // We can retrieve a list of members from the database using a SELECT query.
    // In this case we do not have a WHERE clause because we want to select all
    // of the rows from the database table.
    $query = "
        SELECT
            id,
            teamname,
            teamnumber,
            teamhomelocation,
            startingyear,
            robotweight,
            robotheight,
            robotlength,
            robotwidth,
            numberofwheels,
            wheeltype,
            topspeed,
            numberofdrivemotors,
            haspneumatics,
            numberoftanks,
            canpickuptote,
            maxstackheight,
            canpickupflippedtotes,
            typeoflift,
            canpickupbin,
            binliftheight,
            canpickuppoolnoodle,
            canpushpoolnoodle,
            candepositpoolnoodleinbin,
            noodledepositheight,
            autohascameratracking,
            autocanmove,
            autocanpickuptote,
            autocanstacktote,
            autocanpickupbin,
            autocanstackbin,
            pros,
            cons
        FROM team
        WHERE teamnumber = '".$teamid."'
        LIMIT 1
    ";
    
    try
    {
        // These two statements run the query against your database table.
        $stmt = $db->prepare($query);
        $stmt->execute();
    }
    catch(PDOException $ex)
    {
        // Note: On a production website, you should not output $ex->getMessage().
        // It may provide an attacker with helpful information about your code. 
        die("Failed to run query: " . $ex->getMessage());
    }
        
    // Finally, we can retrieve all of the found rows into an array using fetchAll
    $rows = $stmt->fetchAll(); 
    
    
    $files = scandir('images/' . $teamid . '/'); 
    $ignore = Array(".", "..");
    
    $error = "";
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $target_dir = 'images/' . $teamid . '/';
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if file already exists
        if (file_exists($target_file)) {
            $error =  "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 50000000) {
            $error =  "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $error =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                
            } else {
                $error =  "Sorry, there was an error uploading your file.";
            }
        }
        
    }
    
    
    if (isset($_POST["addcomment"]))
    {
      $query3 = "
            INSERT INTO comments (
                postusername,
                postteamname,
                teamcomment,
                postedfor
            ) VALUES (
                :postusername,
                :postteamname,
                :teamcomment,
                :postedfor
            )
          ";
      $query_params3 = array(
        ':postusername' => htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'),
        ':postteamname' => htmlentities($_SESSION['user']['number'], ENT_QUOTES, 'UTF-8'),
        ':teamcomment' => $_POST['teamcomment'],
        ':postedfor' => $teamid
      );
      try
      {

        // Execute the query to create the user

        $stmt3 = $db->prepare($query3);
        $result3 = $stmt3->execute($query_params3);
      }

      catch(PDOException $ex)
      {

        // Note: On a production website, you should not output $ex->getMessage().
        // It may provide an attacker with helpful information about your code.

        die("Failed to run query: " . $ex->getMessage());
      }

      // This redirects the user back to the login page after they register

      header("Location: profile.php?teamid=" . $teamid);
    }
    
    $query2 = "
        SELECT
            id,
            postusername,
            postteamname,
            teamcomment,
            postedfor
        FROM comments
        WHERE postedfor = '".$teamid."'
    ";
    
    try
    {
        // These two statements run the query against your database table.
        $stmt2 = $db->prepare($query2);
        $stmt2->execute();
    }
    catch(PDOException $ex2)
    {
        // Note: On a production website, you should not output $ex->getMessage().
        // It may provide an attacker with helpful information about your code. 
        die("Failed to run query: " . $ex2->getMessage());
    }
        
    // Finally, we can retrieve all of the found rows into an array using fetchAll
    $rows2 = $stmt2->fetchAll(); 
    
    // Include Header Files (CSS, Javascript, Meta Tags .etc)
    require ("header.php");

    // Bring in menus and sidebar
    require ("nav.php");
?> 
    <?php foreach($rows as $row): ?>
    <?php
      // Start the huge number to text conversion
      
      if ($row['numberofwheels'] == '1')
      {
        $numberofwheels = "4";
      }
      else if ($row['numberofwheels'] == '2')
      {
        $numberofwheels = "6";
      }
      else if ($row['numberofwheels'] == '3')
      {
        $numberofwheels = "8";
      }
      
      if ($row['wheeltype'] == '1')
      {
        $wheeltype = "High-Traction";
      }
      else if ($row['wheeltype'] == '2')
      {
        $wheeltype = "Mecanum";
      }
      else if ($row['wheeltype'] == '3')
      {
        $wheeltype = "Omni";
      }
      else if ($row['wheeltype'] == '4')
      {
        $wheeltype = "Plaction";
      }
      
      if ($row['numberofdrivemotors'] == '1')
      {
        $numberofdrivemotors = "1";
      }
      else if ($row['numberofdrivemotors'] == '2')
      {
        $numberofdrivemotors = "2";
      }
      else if ($row['numberofdrivemotors'] == '3')
      {
        $numberofdrivemotors = "3";
      }
      else if ($row['numberofdrivemotors'] == '4')
      {
        $numberofdrivemotors = "4";
      }
      else if ($row['numberofdrivemotors'] == '5')
      {
        $numberofdrivemotors = "5";
      }
      else if ($row['numberofdrivemotors'] == '6')
      {
        $numberofdrivemotors = "6+";
      }
      
      if ($row['numberoftanks'] == '1')
      {
        $numberoftanks = "1";
      }
      else if ($row['numberoftanks'] == '2')
      {
        $numberoftanks = "2";
      }
      else if ($row['numberoftanks'] == '3')
      {
        $numberoftanks = "3";
      }
      else if ($row['numberoftanks'] == '4')
      {
        $numberoftanks = "4";
      }
      else if ($row['numberoftanks'] == '5')
      {
        $numberoftanks = "5";
      }
      else if ($row['numberoftanks'] == '6')
      {
        $numberoftanks = "6+";
      }
      
      if ($row['haspneumatics'] == '1')
      {
        $haspneumatics = "Has pneumatics";
      }
      else if ($row['haspneumatics'] == '2')
      {
        $haspneumatics = "Does not have pneumatics";
      }
      
      if ($row['numberoftanks'] == '1')
      {
        $numberoftanks = "0";
      }
      else if ($row['numberoftanks'] == '2')
      {
        $numberoftanks = "1";
      }
      else if ($row['numberoftanks'] == '3')
      {
        $numberoftanks = "2";
      }
      else if ($row['numberoftanks'] == '4')
      {
        $numberoftanks = "3";
      }
      else if ($row['numberoftanks'] == '5')
      {
        $numberoftanks = "4";
      }
      else if ($row['numberoftanks'] == '6')
      {
        $numberoftanks = "5";
      }
      else if ($row['numberoftanks'] == '7')
      {
        $numberoftanks = "6+";
      }
      
      if ($row['canpickuptote'] == '1')
      {
        $canpickuptote = "Yes";
      }
      else if ($row['canpickuptote'] == '2')
      {
        $canpickuptote = "No";
      }
      
      if ($row['binliftheight'] == '1')
      {
        $binliftheight = "0";
      }
      else if ($row['binliftheight'] == '2')
      {
        $binliftheight = "1";
      }
      else if ($row['binliftheight'] == '3')
      {
        $binliftheight = "2";
      }
      else if ($row['binliftheight'] == '4')
      {
        $binliftheight = "3";
      }
      else if ($row['binliftheight'] == '5')
      {
        $binliftheight = "4";
      }
      else if ($row['binliftheight'] == '6')
      {
        $binliftheight = "5";
      }
      else if ($row['binliftheight'] == '7')
      {
        $binliftheight = "6";
      }
      
      if ($row['canpickupflippedtotes'] == '1')
      {
        $canpickupflippedtotes = "Yes";
      }
      else if ($row['canpickupflippedtotes'] == '2')
      {
        $canpickupflippedtotes = "No";
      }
      
      if ($row['canpickupbin'] == '1')
      {
        $canpickupbin = "Yes";
      }
      else if ($row['canpickupbin'] == '2')
      {
        $canpickupbin = "No";
      }
      
      if ($row['canpickuppoolnoodle'] == '1')
      {
        $canpickuppoolnoodle = "Yes";
      }
      else if ($row['canpickuppoolnoodle'] == '2')
      {
        $canpickuppoolnoodle = "No";
      }
      
      if ($row['candepositpoolnoodleinbin'] == '1')
      {
        $candepositpoolnoodleinbin = "Yes";
      }
      else if ($row['candepositpoolnoodleinbin'] == '2')
      {
        $candepositpoolnoodleinbin = "No";
      }
      
      if ($row['canpushpoolnoodle'] == '1')
      {
        $canpushpoolnoodle = "Yes";
      }
      else if ($row['canpushpoolnoodle'] == '2')
      {
        $canpushpoolnoodle = "No";
      }
      
      if ($row['noodledepositheight'] == '1')
      {
        $noodledepositheight = "0";
      }
      else if ($row['noodledepositheight'] == '2')
      {
        $noodledepositheight = "1";
      }
      else if ($row['noodledepositheight'] == '3')
      {
        $noodledepositheight = "2";
      }
      else if ($row['noodledepositheight'] == '4')
      {
        $noodledepositheight = "3";
      }
      else if ($row['noodledepositheight'] == '5')
      {
        $noodledepositheight = "4";
      }
      else if ($row['noodledepositheight'] == '6')
      {
        $noodledepositheight = "5";
      }
      else if ($row['noodledepositheight'] == '7')
      {
        $noodledepositheight = "6";
      }
      
      if ($row['maxstackheight'] == '1')
      {
        $maxstackheight = "0";
      }
      else if ($row['maxstackheight'] == '2')
      {
        $maxstackheight = "1";
      }
      else if ($row['maxstackheight'] == '3')
      {
        $maxstackheight = "2";
      }
      else if ($row['maxstackheight'] == '4')
      {
        $maxstackheight = "3";
      }
      else if ($row['maxstackheight'] == '5')
      {
        $maxstackheight = "4";
      }
      else if ($row['maxstackheight'] == '6')
      {
        $maxstackheight = "5";
      }
      else if ($row['maxstackheight'] == '7')
      {
        $maxstackheight = "6";
      }
      
      if ($row['autohascameratracking'] == '1')
      {
        $autohascameratracking = "Yes";
      }
      else if ($row['autohascameratracking'] == '2')
      {
        $autohascameratracking = "No";
      }
      
      if ($row['autocanmove'] == '1')
      {
        $autocanmove = "Yes";
      }
      else if ($row['autocanmove'] == '2')
      {
        $autocanmove = "No";
      }
      
      if ($row['autocanpickuptote'] == '1')
      {
        $autocanpickuptote = "Yes";
      }
      else if ($row['autocanpickuptote'] == '2')
      {
        $autocanpickuptote = "No";
      }
      
      if ($row['autocanstacktote'] == '1')
      {
        $autocanstacktote = "Yes";
      }
      else if ($row['autocanstacktote'] == '2')
      {
        $autocanstacktote = "No";
      }
      
      if ($row['autocanpickupbin'] == '1')
      {
        $autocanpickupbin = "Yes";
      }
      else if ($row['autocanpickupbin'] == '2')
      {
        $autocanpickupbin = "No";
      }
      
      if ($row['autocanstackbin'] == '1')
      {
        $autocanstackbin = "Yes";
      }
      else if ($row['autocanstackbin'] == '2')
      {
        $autocanstackbin = "No";
      }
    ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Team <?php echo $row['teamnumber']; ?>: <?php echo $row['teamname']; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-md-12">
              <h3>General Info</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>From <b><?php echo $row['teamhomelocation']; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>EST <b><?php echo $row['startingyear']; ?></b></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h3>Robot Info</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Weight: <b><?php echo $row['robotweight']; ?></b> Lbs.</p>
            </div>
            <div class="col-md-6">
              <p>Height: <b><?php echo $row['robotheight']; ?></b> In. Tall</p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Length: <b><?php echo $row['robotlength']; ?></b> In. Long</p>
            </div>
            <div class="col-md-6">
              <p>Width: <b><?php echo $row['robotwidth']; ?></b> In. Wide</p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Number of Wheels: <b><?php echo $numberofwheels; ?></b> Wheels</p>
            </div>
            <div class="col-md-6">
              <p>Wheel Type: <b><?php echo $wheeltype; ?></b> Wheels</p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <p>Top Speed: <b><?php echo $row['topspeed']; ?></b> Feet Per Second</p>
            </div>
            <div class="col-md-6">
              <p>Number of Drive Motors: <b><?php echo $numberofdrivemotors; ?></b> Motors</p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p><b><?php echo $haspneumatics; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Number of Air Tanks: <b><?php echo $numberoftanks ?></b> Tanks</p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h3>Game Info</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Can Pickup Tote: <b><?php echo $canpickuptote; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Max Tote Stack Height: <b><?php echo $maxstackheight; ?></b></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Can Pickup Flipped Totes: <b><?php echo $canpickupflippedtotes; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Type of Lift: <b><?php echo $row['typeoflift']; ?></b></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Can Pickup Bin: <b><?php echo $canpickupbin; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Bin Lift Height: <b><?php echo $binliftheight; ?></b> Totes</p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Can Pickup Pool Noodle: <b><?php echo $canpickuppoolnoodle; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Can Push Pool Noodle: <b><?php echo $canpushpoolnoodle; ?></b></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Can Deposit Pool Noodle: <b><?php echo $candepositpoolnoodleinbin; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Noodle Deposit Height: <b><?php echo $noodledepositheight; ?></b> Totes</p>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <h3>Autonomous Info</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Has Camera Tracking: <b><?php echo $autohascameratracking; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Can Move: <b><?php echo $autocanmove; ?></b></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Can Pickup Tote: <b><?php echo $autocanpickuptote; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Can Stack Tote: <b><?php echo $autocanstacktote; ?></b></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Can Pickup Bin: <b><?php echo $autocanpickupbin; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Can Stack Bin: <b><?php echo $autocanstackbin; ?></b></p>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <h3>Pros/Cons</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>Pros: <b><?php echo $row['pros']; ?></b></p>
            </div>
            <div class="col-md-6">
              <p>Cons: <b><?php echo $row['cons']; ?></b></p>
            </div>
          </div>
          
          
        </div>
        <div class="col-lg-6">
          <div class="row">
            <div class="col-md-12">
              <h3>Photo Gallery</h3>
              <?php foreach ($files as $file){
                if(!in_array($file, $ignore)) { ?>
              <div class="col-md-3">
                <div class="galleryimg">
                  <a href="<?php echo 'images/' . $teamid . '/' . $file; ?>"><img class="img img-responsive img-thumbnail full-width" src="<?php echo 'images/' . $teamid . '/' . $file; ?>" /></a>
                </div>
              </div>         
              <?php }?>
              <?php }?>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <h5>Notice Anything Missing?</h5>
              <?php if ($error != "") { ?>
              <div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert">&times;</a>
                  <strong>Error!</strong> <?php print($error);?>
              </div>
              <?php } ?>
              <form method="post" enctype="multipart/form-data" action="profile.php?teamid=<?php echo $teamid;?>">
                <span class="file-input btn btn-primary btn-file">
                  Browse for Photo(s)&hellip; <input type="file" name="fileToUpload" id="fileToUpload">
                </span>
                <input type="submit" class="btn btn-default" value="Upload Image(s)" name="submit">
              </form>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <h3>Comments About Team <?php echo $row['teamnumber']; ?></h3>
            </div>
          </div>
          
          <?php foreach($rows2 as $row2): ?> 
          <div class="row">
            <div class="col-md-12">
              <div class="well">
                <b><?php echo htmlentities($row2['postusername'], ENT_QUOTES, 'UTF-8'); ?> from team <?php echo htmlentities($row2['postteamname'], ENT_QUOTES, 'UTF-8'); ?> says:</b>
                <p><?php echo htmlentities($row2['teamcomment'], ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          </div>
          <?php endforeach; ?> 
          <div class="row">
            <div class="col-md-12">
            <form action="profile.php?teamid=<?php echo $teamid;?>" method="post">
              <div class="input-group">
                <input type="text" name="teamcomment" class="form-control">
                <span class="input-group-btn">
                  <input type="submit" class="btn btn-primary" value="Post Comment" name="addcomment">
                </span>
              </div>
            </form>
            </div>
          </div>
        </div>
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
          <div class="row">
            <h1></h1>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?> 
    </div>

    <?php
      require ("footer.php");
    ?>