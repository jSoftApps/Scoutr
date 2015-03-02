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
  
  // Everything below this point in the file is secured by the login system
    
  $query = "
      SELECT
          id
      FROM users
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
    
  $count = $stmt->rowCount();
  
  // Get teams scouted
    
  $query1 = "
      SELECT
          id
      FROM team
  ";
    
  try
  {
      // These two statements run the query against your database table.
      $stmt1 = $db->prepare($query1);
      $stmt1->execute();
  }
  catch(PDOException $ex)
  {
      // Note: On a production website, you should not output $ex->getMessage().
      // It may provide an attacker with helpful information about your code. 
      die("Failed to run query: " . $ex->getMessage());
  }
    
  $teamcount = $stmt1->rowCount();
    
  // We can display the user's username to them by reading it from the session array.  Remember that because
  // a username is user submitted content we must use htmlentities on it before displaying it to the user.
	
  // Include Header Files (CSS, Javascript, Meta Tags .etc)
  require("header.php");
  
  // Bring in menus and sidebar
  require("nav.php");
?> 
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php print("$count"); ?></div>
                                    <div>Registered User(s)!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php print("$teamcount"); ?></div>
                                    <div>Team(s) Scouted!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">1</div>
                                    <div>Matche(s) Scouted</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php require("average.php"); ?></div>
                                    <div>Average Robot Score</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> FRC 2015 Game Video
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                          <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/W6UYFKNGHJ8?rel=0" frameborder="0" allowfullscreen></iframe>
                          </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> FIRST FMS Twitter
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                                        <a class="twitter-timeline"  href="https://twitter.com/frcfms" data-widget-id="565641928864464897">Tweets by @frcfms</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
          
                            <!-- /.list-group -->
                            <a href="https://twitter.com/frcfms" class="btn btn-default btn-block">View All Tweets</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php 
require("footer.php");
?>