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
              <form action="teamscout.php" method="post">
                <div class="col-lg-12">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input name="teamname" type="text" class="form-control" placeholder="Match ID">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input name="teamname" type="text" class="form-control" placeholder="Team Number">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <select class="form-control" name="autocanpickuptote">
                          <option value="0" selected="" disabled="">Alliance</option>
                          <option value="1">Blue</option>
                          <option value="2">Red</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input name="teamname" type="text" class="form-control" placeholder="Comments on Team">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input name="teamname" type="text" class="form-control" placeholder="Points Scored by Team">
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