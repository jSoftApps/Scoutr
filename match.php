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
    
    $matchnumber = ($_GET['matchnumber']);
    
    // Everything below this point in the file is secured by the login system
    
    // We can display the user's username to them by reading it from the session array.  Remember that because
    // a username is user submitted content we must use htmlentities on it before displaying it to the user.
    
    // We can retrieve a list of members from the database using a SELECT query.
    // In this case we do not have a WHERE clause because we want to select all
    // of the rows from the database table.
    $query = "
        SELECT
            matchid,
            eventlocation,
            teamnumber,
            teamscore,
            teamcomments,
            teamautocomments,
            robotposition
        FROM matchdata
        WHERE matchid = '".$matchnumber."'
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
   
    
    // Include Header Files (CSS, Javascript, Meta Tags .etc)
    require ("header.php");

    // Bring in menus and sidebar
    require ("nav.php");
?> 
<?php foreach($rows as $row): ?>
<?php if ($row['eventlocation'] == 1)
{
  $matchlocation = "West Valley District Event";
}

if ($row['robotposition'] == 1)
{
  $robotposition = "Red 1";
}
else if ($row['robotposition'] == 2)
{
  $robotposition = "Red 2";
}
else if ($row['robotposition'] == 3)
{
  $robotposition = "Red 3";
}
else if ($row['robotposition'] == 4)
{
  $robotposition = "Blue 1";
}
else if ($row['robotposition'] == 5)
{
  $robotposition = "Blue 2";
}
else if ($row['robotposition'] == 6)
{
  $robotposition = "Blue 3";
}
?>
<?php endforeach; ?> 
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Match <?php echo $matchnumber; ?>: <?php echo $matchlocation; ?></h1>
        </div>
        <?php foreach($rows as $row): ?>
        <div class="col-lg-12">
          <div class="panel panel-primary">
            <div class="panel-heading">
              Team <?php echo htmlentities($row['teamnumber'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="panel-body">
              <p><b>Starting Position/Alliance:</b> <?php echo htmlentities($robotposition, ENT_QUOTES, 'UTF-8'); ?></p>
              <p><b>Autonomous Comments:</b> <?php echo htmlentities($row['teamautocomments'], ENT_QUOTES, 'UTF-8'); ?></p>
              <p><b>Teleop Comments:</b> <?php echo htmlentities($row['teamcomments'], ENT_QUOTES, 'UTF-8'); ?></p>
              <h3>Total Points Scored: <?php echo htmlentities($row['teamscore'], ENT_QUOTES, 'UTF-8'); ?></h3>
            </div>
          </div>
        </div>
        <?php endforeach; ?> 
        <!-- /.col-lg-12 -->
      </div>
    </div>
    
    </div>

    <?php
      require ("footer.php");
    ?>