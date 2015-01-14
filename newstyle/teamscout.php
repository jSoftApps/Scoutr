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

// Everything below this point in the file is secured by the login system
// We can display the user's username to them by reading it from the session array.  Remember that because
// a username is user submitted content we must use htmlentities on it before displaying it to the user.
// Start Database Upload Code
// This if statement checks to determine whether the registration form has been submitted
// If it has, then the registration code is run, otherwise the form is displayed

if (!empty($_POST))
{
  $query = "
            SELECT
                1
            FROM team
            WHERE
                teamnumber = :teamnumber
        ";

  // This contains the definitions for any special tokens that we place in
  // our SQL query.  In this case, we are defining a value for the token
  // :teamnumber.  It is possible to insert $_POST['teamnumber'] directly into
  // your $query string; however doing so is very insecure and opens your
  // code up to SQL injection exploits.  Using tokens prevents this.
  // For more information on SQL injections, see Wikipedia:
  // http://en.wikipedia.org/wiki/SQL_Injection

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

  // An INSERT query is used to add new rows to a database table.
  // Again, we are using special tokens (technically called parameters) to
  // protect against SQL injection attacks.

  $query = "
            INSERT INTO team (
                teamname,
                teamnumber,
                teamlocation,
                startingyear,
                weight,
                height,
                shootertype,
                wheeltype,
                wheelnumber,
                motornumber,
                canauto,
                autopoints,
                cancollect,
                caneject,
                canshoothigh,
                canshootlow,
                canthrow,
                cancatch,
                comments,
                problems
            ) VALUES (
                :teamname,
                :teamnumber,
                :teamlocation,
                :startingyear,
                :weight,
                :height,
                :shootertype,
                :wheeltype,
                :wheelnumber,
                :motornumber,
                :canauto,
                :autopoints,
                :cancollect,
                :caneject,
                :canshoothigh,
                :canshootlow,
                :canthrow,
                :cancatch,
                :comments,
                :problems
            )
        ";
  $query_params = array(
    ':teamname' => $_POST['teamname'],
    ':teamnumber' => $_POST['teamnumber'],
    ':teamlocation' => $_POST['teamlocation'],
    ':startingyear' => $_POST['startingyear'],
    ':weight' => $_POST['weight'],
    ':height' => $_POST['height'],
    ':shootertype' => $_POST['shootertype'],
    ':wheeltype' => $_POST['wheeltype'],
    ':wheelnumber' => $_POST['wheelnumber'],
    ':motornumber' => $_POST['motornumber'],
    ':canauto' => $_POST['canauto'],
    ':autopoints' => $_POST['autopoints'],
    ':cancollect' => $_POST['cancollect'],
    ':caneject' => $_POST['caneject'],
    ':canshoothigh' => $_POST['canshoothigh'],
    ':canshootlow' => $_POST['canshootlow'],
    ':canthrow' => $_POST['canthrow'],
    ':cancatch' => $_POST['cancatch'],
    ':comments' => $_POST['comments'],
    ':problems' => $_POST['problems'],
  );
  try
  {

    // Execute the query to upload the team to the database

    $stmt = $db->prepare($query);
    $result = $stmt->execute($query_params);
  }

  catch(PDOException $ex)
  {

    // Note: On a production website, you should not output $ex->getMessage().
    // It may provide an attacker with helpful information about your code.

    die("Failed to run query: " . $ex->getMessage());
  }
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
                <form>
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="page-header" style="margin-top:5px;">Team Info</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Team Name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Team Number">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Team Home Location">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Starting Year">
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
                        <input type="text" class="form-control" placeholder="Robot Weight (Lb)">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Robot Height (In)">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Robot Length (In)">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Robot Width (In)">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
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
                        <input type="text" class="form-control" placeholder="Top Speed (FPS)">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
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
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Has Pneumatics</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Number of Tanks</option>
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
                    <div class="col-md-12">
                      <h3 class="page-header" style="margin-top:5px;">Competition Info</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Can Pickup Tote</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
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
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Can Pickup Flipped Totes</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Type of Lift">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Can Pickup Bin</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
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
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Can Pickup Pool Noodle</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
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
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Can Deposit Noodle in Bin</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
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
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Has Camera Tracking</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
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
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Can Pickup Tote</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
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
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Can Pickup Bin</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control" name="wheeltype">
                          <option value="0" selected="" disabled="">Can Stack Bin</option>
                          <option value="1">Yes</option>
                          <option value="2">No</option>
                        </select>
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