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
    
    // Everything below this point in the file is secured by the login system
    
    // We can display the user's username to them by reading it from the session array.  Remember that because
    // a username is user submitted content we must use htmlentities on it before displaying it to the user.
    
    // Start Database Upload Code
    
    // This if statement checks to determine whether the registration form has been submitted
    // If it has, then the registration code is run, otherwise the form is displayed
    if(!empty($_POST))
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
    }
?> 
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title> SmartAdmin </title>
		<meta name="description" content="">
		<meta name="author" content="">
			
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		
		<!-- #CSS Links -->
		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-skins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/scoutr.css">

		<!-- SmartAdmin RTL Support -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.min.css"> 

		<!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/demo.min.css">

		<!-- #FAVICONS -->
		<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

		<!-- #GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<!-- #APP SCREEN / ICONS -->
		<!-- Specifying a Webpage Icon for Web Clip 
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<link rel="apple-touch-icon" href="img/splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="img/splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="img/splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="img/splash/touch-icon-ipad-retina.png">
		
		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="img/splash/iphone.png" media="screen and (max-device-width: 320px)">

	</head>

	<body class="scoutrstyle">

		<!-- #HEADER -->
		<header id="header">
			<div id="logo-group">

				<!-- PLACE YOUR LOGO HERE -->
				<span id="logo"> <img src="img/scoutrsmall.png" alt="SmartAdmin"> </span>
				<!-- END LOGO PLACEHOLDER -->
			</div>

			<!-- #TOGGLE LAYOUT BUTTONS -->
			<!-- pulled right: nav area -->
			<div class="pull-right">
				
				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
				</div>
				<!-- end collapse menu -->
				
				<!-- #MOBILE -->
				<!-- Top menu profile link : this shows only when top menu is active -->
				<ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
					<li class="">
						<ul class="dropdown-menu pull-right">
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="login.html" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
							</li>
						</ul>
					</li>
				</ul>

				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="logout.php" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->

				<!-- search mobile button (this is hidden till mobile view port) -->
				<div id="search-mobile" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
				</div>
				<!-- end search mobile button -->
				
				<!-- #SEARCH -->
				<!-- input: search field -->
				<form action="search.html" class="header-search pull-right">
					<input id="search-fld" type="text" name="param" placeholder="Find teams and more">
					<button type="submit">
						<i class="fa fa-search"></i>
					</button>
					<a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
				</form>
				<!-- end input: search field -->

				<!-- fullscreen button -->
				<div id="fullscreen" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
				</div>
				<!-- end fullscreen button -->
				
				<!-- #Voice Command: Start Speech -->
				<div id="speech-btn" class="btn-header transparent pull-right hidden-sm hidden-xs">
					<div> 
						<a href="javascript:void(0)" title="Voice Command" data-action="voiceCommand"><i class="fa fa-microphone"></i></a> 
						<div class="popover bottom"><div class="arrow"></div>
							<div class="popover-content">
								<h4 class="vc-title">Voice command activated <br><small>Please speak clearly into the mic</small></h4>
								<h4 class="vc-title-error text-center">
									<i class="fa fa-microphone-slash"></i> Voice command failed
									<br><small class="txt-color-red">Must <strong>"Allow"</strong> Microphone</small>
									<br><small class="txt-color-red">Must have <strong>Internet Connection</strong></small>
								</h4>
								<a href="javascript:void(0);" class="btn btn-success" onclick="commands.help()">See Commands</a> 
								<a href="javascript:void(0);" class="btn bg-color-purple txt-color-white" onclick="$('#speech-btn .popover').fadeOut(50);">Close Popup</a> 
							</div>
						</div>
					</div>
				</div>
				<!-- end voice command -->

			</div>
			<!-- end pulled right: nav area -->

		</header>
		<!-- END HEADER -->

		<!-- #NAVIGATION -->
		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">

			<!-- User info -->
			<div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
					
					<a href="">
						<img src="img/avatars/sunny.png" alt="me"/> 
						<span>
							john.doe 
						</span>
					</a> 
					
				</span>
			</div>
			<!-- end user info -->


			<!-- NAVIGATION : This navigation is also responsive-->
			<nav>
				<ul>
					<li>
						<a href="index.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
					</li>
          <li>
						<a href="index.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-users"></i> <span class="menu-item-parent">My Team Profile</span></a>
					</li>
          <li>
						<a href="index.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">Team List</span></a>
					</li>
          <li class="active">
						<a href="index.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-plus"></i> <span class="menu-item-parent">Scout a Team</span></a>
					</li>
          <li>
						<a href="index.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">Match List</span></a>
					</li>
          <li>
						<a href="index.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-plus"></i> <span class="menu-item-parent">Scout a Match</span></a>
					</li>
				</ul>
			</nav>
			<span class="minifyme" data-action="minifyMenu"> 
				<i class="fa fa-arrow-circle-left hit"></i> 
			</span>

		</aside>
		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">

				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li>Home</li><li>Miscellaneous</li><li>Blank Page</li>
				</ol>
				<!-- end breadcrumb -->

				<!-- You can also add more buttons to the
				ribbon for further usability

				Example below:

				<span class="ribbon-button-alignment pull-right">
				<span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
				<span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
				<span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
				</span> -->

			</div>
			<!-- END RIBBON -->
			
			

			<!-- MAIN CONTENT -->
			<div id="content">

				<!-- row -->
				<div class="row">
					
					<!-- col -->
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark">
							
							<!-- PAGE HEADER -->
							<i class="fa-fw fa fa-home"></i> 
								Page Header 
							<span>>  
								Subtitle
							</span>
						</h1>
					</div>
					<!-- end col -->
					
				</div>
				<!-- end row -->
				
				<!--
					The ID "widget-grid" will start to initialize all widgets below 
					You do not need to use widgets if you dont want to. Simply remove 
					the <section></section> and you can use wells or panels instead 
					-->
				
				<!-- widget grid -->
				<section id="widget-grid" class="">
				
					<!-- row -->
					<div class="row">
						
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false"	data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<!-- widget options:
									usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
									
									data-widget-colorbutton="false"	
									data-widget-editbutton="false"
									data-widget-togglebutton="false"
									data-widget-deletebutton="false"
									data-widget-fullscreenbutton="false"
									data-widget-custombutton="false"
									data-widget-collapsed="true" 
									data-widget-sortable="false"
									
								-->
								<header>
									<span class="widget-icon"> <i class="fa fa-plus"></i> </span>
									<h2>Scout a Team </h2>				
									
								</header>
				
								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body">
										
										<form id="order-form" class="smart-form" method="post" novalidate="novalidate">
                      <header>
                        Basic Team Information
                      </header>

                      <fieldset>
                        <div class="row">
                          <section class="col col-6">
                            <label class="input"> <i class="icon-append fa fa-user"></i>
                              <input type="text" name="teamname" placeholder="Team Name">
                            </label>
                          </section>
                          <section class="col col-6">
                            <label class="input"> <i class="icon-append fa fa-briefcase"></i>
                              <input type="text" name="teamnumber" placeholder="Team Number">
                            </label>
                          </section>
                        </div>

                        <div class="row">
                          <section class="col col-6">
                            <label class="input"> <i class="icon-append fa fa-envelope-o"></i>
                              <input type="text" name="teamlocation" placeholder="Team Home Location">
                            </label>
                          </section>
                          <section class="col col-6">
                            <label class="input"> <i class="icon-append fa fa-envelope-o"></i>
                              <input type="text" name="startingyear" placeholder="Starting Year">
                            </label>
                          </section>
                        </div>
                      </fieldset>
                      
                      <header>
                        Robot Physical Information
                      </header>
                      
                      <fieldset>
                        <div class="row">
                          <section class="col col-6">
                            <label class="input"> <i class="icon-append fa fa-user"></i>
                              <input type="text" name="weight" placeholder="Robot Weight (Lbs)">
                            </label>
                          </section>
                          <section class="col col-6">
                            <label class="input"> <i class="icon-append fa fa-briefcase"></i>
                              <input type="text" name="height" placeholder="Robot Height (In)">
                            </label>
                          </section>
                        </div>

                        <div class="row">
                          <section class="col col-6">
                            <label class="select">
                              <select name="shootertype">
                                <option value="0" selected="" disabled="">Shooter Power Source</option>
                                <option value="1">Pneumatic</option>
                                <option value="2">Spring</option>
                                <option value="3">Elastic</option>
                                <option value="4">Electric</option>
                                <option value="5">Other</option>
                              </select> <i></i> </label>
                          </section>
                          <section class="col col-6">
                            <label class="select">
                              <select name="wheeltype">
                                <option value="0" selected="" disabled="">Wheel Type</option>
                                <option value="1">High-Traction</option>
                                <option value="2">Mecanum</option>
                                <option value="3">Omni</option>
                                <option value="4">Plaction</option>
                                <option value="5">Caster</option>
                                <option value="6">Pneumatic</option>
                                <option value="7">Other</option>
                              </select> <i></i> </label>
                          </section>
                        </div>
                        
                        <div class="row">
                          <section class="col col-6">
                            <label class="input"> <i class="icon-append fa fa-user"></i>
                              <input type="text" name="wheelnumber" placeholder="Number of Wheels">
                            </label>
                          </section>
                          <section class="col col-6">
                            <label class="input"> <i class="icon-append fa fa-briefcase"></i>
                              <input type="text" name="motornumber" placeholder="Number of Drive Motors">
                            </label>
                          </section>
                        </div>
                      </fieldset>
                      
                      <header>
                        Autonomous
                      </header>
                      
                      <fieldset>
                        <div class="row">
                          <section class="col col-6">
                            <label class="select">
                              <select name="canauto">
                                <option value="0" selected="" disabled="">Can Autonomous</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                              </select> <i></i> </label>
                          </section>
                          <section class="col col-6">
                            <label class="input"> <i class="icon-append fa fa-briefcase"></i>
                              <input type="text" name="autopoints" placeholder="Average Autonomous Score">
                            </label>
                          </section>
                        </div>
                      </fieldset>
                      
                      <header>
                        Game Information
                      </header>
                      
                      <fieldset>
                        <div class="row">
                          <section class="col col-6">
                            <label class="select">
                              <select name="cancollect">
                                <option value="0" selected="" disabled="">Can Collect Ball</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                              </select> <i></i> </label>
                          </section>
                          <section class="col col-6">
                            <label class="select">
                              <select name="caneject">
                                <option value="0" selected="" disabled="">Can Eject Ball</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                              </select> <i></i> </label>
                          </section>
                        </div>
                        
                        <div class="row">
                          <section class="col col-6">
                            <label class="select">
                              <select name="canshoothigh">
                                <option value="0" selected="" disabled="">Can Shoot High Goal</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                              </select> <i></i> </label>
                          </section>
                          <section class="col col-6">
                            <label class="select">
                              <select name="canshootlow">
                                <option value="0" selected="" disabled="">Can Shoot Low Goal</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                              </select> <i></i> </label>
                          </section>
                        </div>
                        
                        <div class="row">
                          <section class="col col-6">
                            <label class="select">
                              <select name="canthrow">
                                <option value="0" selected="" disabled="">Can Throw over Truss</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                              </select> <i></i> </label>
                          </section>
                          <section class="col col-6">
                            <label class="select">
                              <select name="cancatch">
                                <option value="0" selected="" disabled="">Can Catch from Truss</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                              </select> <i></i> </label>
                          </section>
                        </div>
                      </fieldset>
                      
                      <header>
                        Robot Image
                      </header>
                      
                      <fieldset>
                        <div class="row">
                          <section class="col col-12">
                            <label class="textarea"> 										
                              <input type="file" class="upload" name="fileToUpload" id="fileToUpload">
                            </label>
                          </section>
                          
                        </div>
                      </fieldset>
                      
                      <header>
                        Comments
                      </header>
                      
                      <fieldset>
                        <div class="row">
                          <section class="col col-6">
                            <label class="textarea"> 										
                              <textarea rows="3" name="comments" placeholder="General Comments"></textarea> 
                            </label>
                          </section>
                          <section class="col col-6">
                            <label class="textarea"> 										
                              <textarea rows="3" name="problems" placeholder="Known Problems"></textarea> 
                            </label>
                          </section>
                        </div>
                      </fieldset>
                      
                      <footer>
                        <button type="submit" class="btn btn-primary">
                          Submit
                        </button>
                      </footer>
                    </form>
				
									</div>
									<!-- end widget content -->
									
								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->
				
						</article>
						<!-- WIDGET END -->
						
					</div>
				
					<!-- end row -->
				
					<!-- row -->
				
					<div class="row">
				
						<!-- a blank row to get started -->
						<div class="col-sm-12">
							<!-- your contents here -->
						</div>
							
					</div>
				
					<!-- end row -->
				
				</section>
				<!-- end widget grid -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->

		<!-- PAGE FOOTER -->
		<div class="page-footer">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<span class="txt-color-white">Scoutr v 2.0.0 <span class="hidden-xs"> - Designed by jSoft Apps.</span> Copyright © 2015</span>
				</div>

				<div class="col-xs-6 col-sm-6 text-right hidden-xs">
					
				</div>
			</div>
		</div>
		<!-- END PAGE FOOTER -->

		<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
		Note: These tiles are completely responsive,
		you can add as many as you like
		-->
		<div id="shortcut">
			<ul>
				<li>
					<a href="inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
				</li>
				<li>
					<a href="calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
				</li>
				<li>
					<a href="gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
				</li>
				<li>
					<a href="invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
				</li>
				<li>
					<a href="gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
				</li>
				<li>
					<a href="profile.html" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
				</li>
			</ul>
		</div>
		<!-- END SHORTCUT AREA -->

		<!--================================================== -->

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>

		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');
			}
		</script>

		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 

		<!-- BOOTSTRAP JS -->
		<script src="js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

		<!-- SPARKLINES -->
		<script src="js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>

		<!-- JQUERY MASKED INPUT -->
		<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>

		<!-- JQUERY SELECT2 INPUT -->
		<script src="js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="js/plugin/fastclick/fastclick.min.js"></script>

		<!--[if IE 8]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->

		<!-- Demo purpose only -->
		<script src="js/demo.min.js"></script>

		<!-- MAIN APP JS FILE -->
		<script src="js/app.min.js"></script>

		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="js/speech/voicecommand.min.js"></script>

		<!-- SmartChat UI : plugin -->
		<script src="js/smart-chat-ui/smart.chat.ui.min.js"></script>
		<script src="js/smart-chat-ui/smart.chat.manager.min.js"></script>

		<!-- PAGE RELATED PLUGIN(S) 
		<script src="..."></script>-->

		<script type="text/javascript">

			$(document).ready(function() {
			 	
				/* DO NOT REMOVE : GLOBAL FUNCTIONS!
				 *
				 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
				 *
				 * // activate tooltips
				 * $("[rel=tooltip]").tooltip();
				 *
				 * // activate popovers
				 * $("[rel=popover]").popover();
				 *
				 * // activate popovers with hover states
				 * $("[rel=popover-hover]").popover({ trigger: "hover" });
				 *
				 * // activate inline charts
				 * runAllCharts();
				 *
				 * // setup widgets
				 * setup_widgets_desktop();
				 *
				 * // run form elements
				 * runAllForms();
				 *
				 ********************************
				 *
				 * pageSetUp() is needed whenever you load a page.
				 * It initializes and checks for all basic elements of the page
				 * and makes rendering easier.
				 *
				 */
				
				 pageSetUp();
				 
				/*
				 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
				 * eg alert("my home function");
				 * 
				 * var pagefunction = function() {
				 *   ...
				 * }
				 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
				 * 
				 * TO LOAD A SCRIPT:
				 * var pagefunction = function (){ 
				 *  loadScript(".../plugin.js", run_after_loaded);	
				 * }
				 * 
				 * OR
				 * 
				 * loadScript(".../plugin.js", run_after_loaded);
				 */
				
			})
		
		</script>

		<!-- Your GOOGLE ANALYTICS CODE Below -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
				_gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
				_gaq.push(['_trackPageview']);
			
			(function() {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();

		</script>

	</body>

</html>