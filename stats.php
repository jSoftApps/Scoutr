<?php

	// index.php
	// Copyright (C) 2015 jSoft Apps, All Rights Reserved
	// Licensed under the CC-BY-NC-SA version 4.0
	// This is the main dashboard for Scoutr.
	// Here you will find a bunch of random information, as well as streams and stats!

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

  // Include Header Files (CSS, Javascript, Meta Tags .etc)
  require("header.php");
  
  // Bring in menus and sidebar
  require("nav.php");
?> 

          <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Scouting Statistics</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h3>Average Robot Score <?php require("average.php"); ?></h3>
                    <h3>Match Score Over Time</h3>
                    <div id="myfirstchart" style="height: 250px;"></div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
          </div>
    <!-- /#wrapper -->
<?php 
require("footer.php");
?>