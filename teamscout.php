<?php

  // teamscout.php
  // Copyright (C) 2015 jSoft Apps, All Rights Reserved
  // Licensed under the CC-BY-NC-SA version 4.0
  // This file contains the code to create and upload teams to the MySQL database.

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
    
    // We will use this SQL query to see whether the team number entered by the
    // user is already in use.  A SELECT query is used to retrieve data from the database.
    // :teamnumber is a special token, we will substitute a real value in its place when
    // we execute the query.
    $query = "
        SELECT
            1
        FROM team
        WHERE
            teamnumber = :teamnumber
    ";
    
    $query_params = array(
        ':teamnumber' => $_POST['teamnumber']
    );
    
    try
    {
        // These two statements run the query against your database table.
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
        // Note: On a production website, you should not output $ex->getMessage().
        // It may provide an attacker with helpful information about your code. 
        die("Failed to run query: " . $ex->getMessage());
    }
        
    // The fetch() method returns an array representing the "next" row from
    // the selected results, or false if there are no more rows to fetch.
    $row = $stmt->fetch();
        
    // If a row was returned, then we know a matching team number was found in
    // the database already and we should not allow the user to continue.
    if($row)
    {
        $query1 = "
          DELETE FROM team
          WHERE teamnumber = :teamnumber
        ";
        $query_params1 = array(
          ':teamnumber' => $_POST['teamnumber']
        );
        $stmt1 = $db->prepare($query1);
        $stmt1->execute($query_params1);
    }
    
      $query = "
        INSERT INTO team 
        (
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
        ) VALUES 
        (
          :teamname,
          :teamnumber,
          :teamhomelocation,
          :startingyear,
          :robotweight,
          :robotheight,
          :robotlength,
          :robotwidth,
          :numberofwheels,
          :wheeltype,
          :topspeed,
          :numberofdrivemotors,
          :haspneumatics,
          :numberoftanks,
          :canpickuptote,
          :maxstackheight,
          :canpickupflippedtotes,
          :typeoflift,
          :canpickupbin,
          :binliftheight,
          :canpickuppoolnoodle,
          :canpushpoolnoodle,
          :candepositpoolnoodleinbin,
          :noodledepositheight,
          :autohascameratracking,
          :autocanmove,
          :autocanpickuptote,
          :autocanstacktote,
          :autocanpickupbin,
          :autocanstackbin,
          :pros,
          :cons
        )
      ";
    
    $query_params = array
    (
      ':teamname' => $_POST['teamname'],
      ':teamnumber' => $_POST['teamnumber'],
      ':teamhomelocation' => $_POST['teamhomelocation'],
      ':startingyear' => $_POST['startingyear'],
      ':robotweight' => $_POST['robotweight'],
      ':robotheight' => $_POST['robotheight'],
      ':robotlength' => $_POST['robotlength'],
      ':robotwidth' => $_POST['robotwidth'],
      ':numberofwheels' => $_POST['numberofwheels'],
      ':wheeltype' => $_POST['wheeltype'],
      ':topspeed' => $_POST['topspeed'],
      ':numberofdrivemotors' => $_POST['numberofdrivemotors'],
      ':haspneumatics' => $_POST['haspneumatics'],
      ':numberoftanks' => $_POST['numberoftanks'],
      ':canpickuptote' => $_POST['canpickuptote'],
      ':maxstackheight' => $_POST['maxstackheight'],
      ':canpickupflippedtotes' => $_POST['canpickupflippedtotes'],
      ':typeoflift' => $_POST['typeoflift'],
      ':canpickupbin' => $_POST['canpickupbin'],
      ':binliftheight' => $_POST['binliftheight'],
      ':canpickuppoolnoodle' => $_POST['canpickuppoolnoodle'],
      ':canpushpoolnoodle' => $_POST['canpushpoolnoodle'],
      ':candepositpoolnoodleinbin' => $_POST['candepositpoolnoodleinbin'],
      ':noodledepositheight' => $_POST['noodledepositheight'],
      ':autohascameratracking' => $_POST['autohascameratracking'],
      ':autocanmove' => $_POST['autocanmove'],
      ':autocanpickuptote' => $_POST['autocanpickuptote'],
      ':autocanstacktote' => $_POST['autocanstacktote'],
      ':autocanpickupbin' => $_POST['autocanpickupbin'],
      ':autocanstackbin' => $_POST['autocanstackbin'],
      ':pros' => $_POST['pros'],
      ':cons' => $_POST['cons']
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
    
    if (!file_exists('images/' .$_POST['teamnumber'])) {
      mkdir('images/' .$_POST['teamnumber'], 0777, true);
    }
        
    // This redirects the user back to the login page after they register
    header("Location: profile.php?teamid=" . $_POST['teamnumber']);
    
  }

  // Include Header Files (CSS, Javascript, Meta Tags .etc)

  require ("header.php");

  // Bring in menus and sidebar

  require ("nav.php");

?> 
        
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Team Scouting</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="teamscout.php" method="post">
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="page-header" style="margin-top:5px;">Team Info</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="teamname" type="text" class="form-control" placeholder="Team Name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="teamnumber" type="text" class="form-control" placeholder="Team Number">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="teamhomelocation" type="text" class="form-control" placeholder="Team Home Location">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="startingyear" type="text" class="form-control" placeholder="Starting Year">
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="page-header" style="margin-top:5px;">Robot Info</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="robotweight" type="text" class="form-control" placeholder="Robot Weight (Lb)">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="robotheight" type="text" class="form-control" placeholder="Robot Height (In)">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="robotlength" type="text" class="form-control" placeholder="Robot Length (In)">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="robotwidth" type="text" class="form-control" placeholder="Robot Width (In)">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="numberofwheels">
                          <option value="0" selected="" disabled="">Number of Wheels</option>
                          <option value="1">4</option>
                          <option value="2">6</option>
                          <option value="3">8</option>
                          <option value="4">Other</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Wheel Type</option>
                          <option value="1">High-Traction</option>
                          <option value="2">Mecanum</option>
                          <option value="3">Omni</option>
                          <option value="4">Plaction</option>
                          <option value="5">Caster</option>
                          <option value="6">Pneumatic</option>
                          <option value="7">Other</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="topspeed" type="text" class="form-control" placeholder="Top Speed (FPS)">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="numberofdrivemotors">
                          <option value="0" selected="" disabled="">Number of Drive Motors</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6+</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="haspneumatics">
                          <option value="0" selected="" disabled="">Has Pneumatics</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="numberoftanks">
                          <option value="0" selected="" disabled="">Number of Tanks</option>
                          <option value="1">0</option>
                          <option value="2">1</option>
                          <option value="3">2</option>
                          <option value="4">3</option>
                          <option value="5">4</option>
                          <option value="6">5</option>
                          <option value="7">6+</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="page-header" style="margin-top:5px;">Competition Info</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="canpickuptote">
                          <option value="0" selected="" disabled="">Can Pickup Tote</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="maxstackheight">
                          <option value="0" selected="" disabled="">Max Stack Height (Totes)</option>
                          <option value="1">0</option>
                          <option value="2">1</option>
                          <option value="3">2</option>
                          <option value="4">3</option>
                          <option value="5">4</option>
                          <option value="6">5</option>
                          <option value="7">6</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="canpickupflippedtotes">
                          <option value="0" selected="" disabled="">Can Pickup Flipped Totes</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input name="typeoflift" type="text" class="form-control" placeholder="Type of Lift">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="canpickupbin">
                          <option value="0" selected="" disabled="">Can Pickup Bin</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="binliftheight">
                          <option value="0" selected="" disabled="">Bin Lift Height (Totes)</option>
                          <option value="1">0</option>
                          <option value="2">1</option>
                          <option value="3">2</option>
                          <option value="4">3</option>
                          <option value="5">4</option>
                          <option value="6">5</option>
                          <option value="7">6</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="canpickuppoolnoodle">
                          <option value="0" selected="" disabled="">Can Pickup Pool Noodle</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="canpushpoolnoodle">
                          <option value="0" selected="" disabled="">Can Push Pool Noodle</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="candepositpoolnoodleinbin">
                          <option value="0" selected="" disabled="">Can Deposit Noodle in Bin</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="noodledepositheight">
                          <option value="0" selected="" disabled="">Noodle Deposit Height (Totes)</option>
                          <option value="1">0</option>
                          <option value="2">1</option>
                          <option value="3">2</option>
                          <option value="4">3</option>
                          <option value="5">4</option>
                          <option value="6">5</option>
                          <option value="7">6</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="page-header" style="margin-top:5px;">Autonomous Info</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="autohascameratracking">
                          <option value="0" selected="" disabled="">Has Camera Tracking</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="autocanmove">
                          <option value="0" selected="" disabled="">Can Move</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="autocanpickuptote">
                          <option value="0" selected="" disabled="">Can Pickup Tote</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="autocanstacktote">
                          <option value="0" selected="" disabled="">Can Stack Tote</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="autocanpickupbin">
                          <option value="0" selected="" disabled="">Can Pickup Bin</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="autocanstackbin">
                          <option value="0" selected="" disabled="">Can Stack Bin</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="page-header" style="margin-top:5px;">Other Info</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <textarea name="pros" class="form-control" placeholder="Cons">Pros</textarea>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <textarea name="cons" class="form-control" placeholder="Cons">Cons</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg">
                          Scout Team
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
  
                    
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php
require ("footer.php");
?>