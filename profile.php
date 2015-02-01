<?php

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
    
    if(isset($_POST['team'])){
      $team = $_GET['team'];
    }
    else {
      $team = htmlentities($_SESSION['user']['number'], ENT_QUOTES, 'UTF-8');
    }

    
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
            autocanpickupbin,
            autocanstackbin,
            pros,
            cons
        FROM team
        WHERE teamnumber = '".$team."'
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
    
    
    $files = scandir('images/' . $team . '/'); 
    $ignore = Array(".", "..");
    
    $error = "";
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $target_dir = 'images/' . $team . '/';
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $error = "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $error =  "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
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
    
    
    
    
    $query2 = "
        SELECT
            id,
            postusername,
            postteamname,
            teamcomment,
            postedfor
        FROM comments
        WHERE postedfor = '".$team."'
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
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Team <?php echo $row['teamnumber']; ?>: <?php echo $row['teamname']; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
      </div>
      <div class="row">
        <div class="col-lg-6">
          <h3>General Info</h3>
          <p>From <?php echo $row['teamhomelocation']; ?></p>
          <p>EST <?php echo $row['startingyear']; ?></p>
        </div>
        <div class="col-lg-6">
          <div class="row">
            <div class="col-md-12">
              <h3>Photo Gallery</h3>
              <?php foreach ($files as $file){
                if(!in_array($file, $ignore)) { ?>
              <div class="col-md-3">
                <div class="galleryimg">
                  <a href="<?php echo 'images/' . $team . '/' . $file; ?>"><img class="img img-responsive img-thumbnail full-width" src="<?php echo 'images/' . $team . '/' . $file; ?>" /></a>
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
              <form method="post" enctype="multipart/form-data" action="profile.php?team=<?php echo $team;?>">
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
              <div class="input-group">
                <input type="text" class="form-control">
                <span class="input-group-btn">
                  <button class="btn btn-primary" type="button">Post Comment</button>
                </span>
              </div>
            </div>
          </div>
        </div>
        <!-- /.col-lg-12 -->
      </div>
    </div>
    <?php endforeach; ?> 
    </div>

    <?php
      require ("footer.php");
    ?>