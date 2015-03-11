<?php

  // matchscout.php
  // Copyright (C) 2015 jSoft Apps, All Rights Reserved
  // Licensed under the CC-BY-NC-SA version 4.0
  // This file contains the code to create and upload matches to the MySQL database.

  // First we execute our common code to connection to the database and start the session
  require ("includes/common.php");

  // At the top of the page we check to see whether the user is logged in or not

  if (empty($_SESSION['user']))
  {

    // If they are not, we redirect them to the login page.

    header("Location: login.php");

    // Remember that this die statement is absolutely critical.  Without it,
    // people can view your members-only content without logging in.

    die("Redirecting to login.php");
  }
  
  if(!empty($_POST))
  {
    if(empty($_POST['teamnumber']))
    {
      die("Please Specify a Team Number");
    }
        
    // If a row was returned, then we know a matching team number was found in
    // the database already and we should not allow the user to continue.
    
      $query = "
        INSERT INTO matchdata 
        (
          matchid,
          eventlocation,
          teamnumber,
          teamscore,
          teamcomments,
          teamautocomments,
          robotposition
        ) VALUES 
        (
          :matchid,
          :eventlocation,
          :teamnumber,
          :teamscore,
          :teamcomments,
          :teamautocomments,
          :robotposition
        )
      ";
    
    $query_params = array
    (
      ':matchid' => $_POST['matchid'],
      ':eventlocation' => $_POST['eventlocation'],
      ':teamnumber' => $_POST['teamnumber'],
      ':teamscore' => $_POST['teamscore'],
      ':teamcomments' => $_POST['teamcomments'],
      ':teamautocomments' => $_POST['teamautocomments'],
      ':robotposition' => $_POST['robotposition']
    );
    
    try
    {
      // Execute the query to create the user
      $stmt = $db->prepare($query);
      $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
      // Note: On a production website, you should not output $ex->getMessage().
      // It may provide an attacker with helpful information about your code. 
      die("Failed to run query: " . $ex->getMessage());
    }
        
    // This redirects the user back to the login page after they register
    header("Location: match.php?matchnumber=" . $_POST['matchid']);
    
  }
  
  // Include Header Files (CSS, Javascript, Meta Tags .etc)
  require ("header.php");

  // Bring in menus and sidebar
  require ("nav.php");
  
?>

        <div id="page-wrapper">
          <div class="row">
            <div class="col-lg-12">
              <h1 class="page-header">Match Scouting</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <form action="matchscout.php" method="post">
                <div class="col-lg-12">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input name="matchid" type="text" class="form-control" placeholder="Match ID">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input name="teamnumber" type="text" class="form-control" placeholder="Team Number">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <select class="form-control" name="eventlocation">
                          <option value="0" selected="" disabled="">Event Location</option>
                          <option value="1">West Valley District Event</option>
                          <option value="2">Auburn District Event</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <select class="form-control" name="robotposition">
                          <option value="0" selected="" disabled="">Robot Position</option>
                          <option value="1">Red 1</option>
                          <option value="2">Red 2</option>
                          <option value="2">Red 3</option>
                          <option value="2">Blue 1</option>
                          <option value="2">Blue 2</option>
                          <option value="2">Blue 3</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <textarea name="teamautocomments" type="text" class="form-control"  placeholder="Comments on Team Autonommous"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <textarea name="teamcomments" type="text" class="form-control"  placeholder="Comments on Team"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input name="teamscore" type="text" class="form-control" placeholder="Points Scored by Alliance">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg">
                          Scout Match
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.col-lg-12 -->
          </div>
        </div>
      </div>