<?php

	// login.php
	// Copyright (C) 2015 jSoft Apps, All Rights Reserved
	// Licensed under the CC-BY-NC-SA version 4.0
	// This is the file containing all of scoutr's login code
	// It handles encryption and the creation of the user cookie
 
  
    // First we execute our common code to connection to the database and start the session
    require("includes/common.php");
   
  if (!empty($_SESSION['user']))
  {

    // If they are not, we redirect them to the login page.

    header("Location: index.php");

    // Remember that this die statement is absolutely critical.  Without it,
    // people can view your members-only content without logging in.

    die("Redirecting to index.php");
  }
  $error = '';
   
    // This variable will be used to re-display the user's username to them in the
    // login form if they fail to enter the correct password.  It is initialized here
    // to an empty value, which will be shown if the user has not submitted the form.
    $submitted_username = '';
    
    // This if statement checks to determine whether the login form has been submitted
    // If it has, then the login code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
        // This query retreives the user's information from the database using
        // their username.
        $query = "
            SELECT
                id,
                username,
                password,
                salt,
                number
            FROM users
            WHERE
                username = :username
        ";
        
        // The parameter values
        $query_params = array(
            ':username' => $_POST['username']
        );
        
        try
        {
            // Execute the query against the database
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            // Note: On a production website, you should not output $ex->getMessage().
            // It may provide an attacker with helpful information about your code. 
            die("Failed to run query: " . $ex->getMessage());
        }
        
        // This variable tells us whether the user has successfully logged in or not.
        // We initialize it to false, assuming they have not.
        // If we determine that they have entered the right details, then we switch it to true.
        $login_ok = false;
        
        // Retrieve the user data from the database.  If $row is false, then the username
        // they entered is not registered.
        $row = $stmt->fetch();
        if($row)
        {
            // Using the password submitted by the user and the salt stored in the database,
            // we now check to see whether the passwords match by hashing the submitted password
            // and comparing it to the hashed version already stored in the database.
            $check_password = hash('sha256', $_POST['password'] . $row['salt']);
            for($round = 0; $round < 65536; $round++)
            {
                $check_password = hash('sha256', $check_password . $row['salt']);
            }
            
            if($check_password === $row['password'])
            {
                // If they do, then we flip this to true
                $login_ok = true;
            }
        }
        
        // If the user logged in successfully, then we send them to the private members-only page
        // Otherwise, we display a login failed message and show the login form again
        if($login_ok)
        {
            // Here I am preparing to store the $row array into the $_SESSION by
            // removing the salt and password values from it.  Although $_SESSION is
            // stored on the server-side, there is no reason to store sensitive values
            // in it unless you have to.  Thus, it is best practice to remove these
            // sensitive values first.
            unset($row['salt']);
            unset($row['password']);
            
            // This stores the user's data into the session at the index 'user'.
            // We will check this index on the private members-only page to determine whether
            // or not the user is logged in.  We can also use it to retrieve
            // the user's details.
            $_SESSION['user'] = $row;
            
            // Redirect the user to the private members-only page.
            header("Location: index.php");
            die("Redirecting to: index.php");
        }
        else
        {
            // Tell the user they failed
            $error="Incorrect username or password!";
            
            // Show them their username again so all they have to do is enter a new
            // password.  The use of htmlentities prevents XSS attacks.  You should
            // always use htmlentities on user submitted values before displaying them
            // to any users (including the user that submitted them).  For more information:
            // http://en.wikipedia.org/wiki/XSS_attack
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
        }
    }
    
?> 
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - Scoutr v 2.0.0</title>

    <!-- Bootstrap Core CSS -->
    <link href="assets/plugins/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Metro Theme -->
    <link href="assets/css/metro-bootstrap.css" rel="stylesheet">
    
    
    <!-- Bootstrap Metro Menu Restyle -->
    <link href="assets/css/customnav.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="assets/plugins/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="assets/css/timeline.css" rel="stylesheet">

    <!-- SB Admin CSS -->
    <link href="assets/css/sb-admin-m.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="assets/plugins/morrisjs/morris.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper" style="background:#F8F8F8;">

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                    <?php if ($error != "") { ?>
                      <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Error!</strong> <?php print($error);?>
                      </div>
                    <?php } ?>
                        <form action="login.php" id="smart-form-register" class="smart-form client-form" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-primary btn-block">
                                	Login
                                </button>
                                <a href="register.php" class="btn btn-block btn-default">Register</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php 
require("footer.php");
?>