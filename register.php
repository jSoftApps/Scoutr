<?php

	// register.php
	// Copyright (C) 2015 jSoft Apps, All Rights Reserved
	// Licensed under the CC-BY-NC-SA version 4.0
	// This is the file containing all of scoutr's registration code
	// It handles encryption and the publishing of information to the database
  
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
  
    // This if statement checks to determine whether the registration form has been submitted
    // If it has, then the registration code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
        // Ensure that the user has entered a non-empty username
        if(empty($_POST['username']))
        {
            // Display error message
            header('Location: register.php?error=2');
            die();
        }
        
        // Ensure that the user has entered a non-empty username
        if(empty($_POST['number']))
        {
            // Display error message
            header('Location: register.php?error=4');
            die();
        }
        
        // Ensure that the user has entered a non-empty password
        if(empty($_POST['password']))
        {
          header('Location: register.php?error=3');
          die();
        }
        
        // We will use this SQL query to see whether the username entered by the
        // user is already in use.  A SELECT query is used to retrieve data from the database.
        // :username is a special token, we will substitute a real value in its place when
        // we execute the query.
        $query = "
            SELECT
                1
            FROM users
            WHERE
                username = :username
        ";
        
        // This contains the definitions for any special tokens that we place in
        // our SQL query.  In this case, we are defining a value for the token
        // :username.  It is possible to insert $_POST['username'] directly into
        // your $query string; however doing so is very insecure and opens your
        // code up to SQL injection exploits.  Using tokens prevents this.
        // For more information on SQL injections, see Wikipedia:
        // http://en.wikipedia.org/wiki/SQL_Injection
        $query_params = array(
            ':username' => $_POST['username']
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
        
        // If a row was returned, then we know a matching username was found in
        // the database already and we should not allow the user to continue.
        if($row)
        {
          header('Location: register.php?error=1');
          die();
        }
        
        // An INSERT query is used to add new rows to a database table.
        // Again, we are using special tokens (technically called parameters) to
        // protect against SQL injection attacks.
        $query = "
            INSERT INTO users (
                username,
                password,
                salt,
                number
            ) VALUES (
                :username,
                :password,
                :salt,
                :number
            )
        ";
        
        // A salt is randomly generated here to protect again brute force attacks
        // and rainbow table attacks.  The following statement generates a hex
        // representation of an 8 byte salt.  Representing this in hex provides
        // no additional security, but makes it easier for humans to read.
        // For more information:
        // http://en.wikipedia.org/wiki/Salt_%28cryptography%29
        // http://en.wikipedia.org/wiki/Brute-force_attack
        // http://en.wikipedia.org/wiki/Rainbow_table
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        
        // This hashes the password with the salt so that it can be stored securely
        // in your database.  The output of this next statement is a 64 byte hex
        // string representing the 32 byte sha256 hash of the password.  The original
        // password cannot be recovered from the hash.  For more information:
        // http://en.wikipedia.org/wiki/Cryptographic_hash_function
        $password = hash('sha256', $_POST['password'] . $salt);
        
        // Next we hash the hash value 65536 more times.  The purpose of this is to
        // protect against brute force attacks.  Now an attacker must compute the hash 65537
        // times for each guess they make against a password, whereas if the password
        // were hashed only once the attacker would have been able to make 65537 different 
        // guesses in the same amount of time instead of only one.
        for($round = 0; $round < 65536; $round++)
        {
            $password = hash('sha256', $password . $salt);
        }
        
        // Here we prepare our tokens for insertion into the SQL query.  We do not
        // store the original password; only the hashed version of it.  We do store
        // the salt (in its plaintext form; this is not a security risk).
        $query_params = array(
            ':username' => $_POST['username'],
            ':password' => $password,
            ':salt' => $salt,
            ':number' => $_POST['number']
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
        header("Location: login.php");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to login.php");
	
	}
  
  if ($_GET['error'] == '1')
        {
          $error = "This username is already registered";
        }
        else if ($_GET['error'] == '2')
        {
          $error = "Please enter a username";
        }
        else if ($_GET['error'] == '3')
        {
          $error = "Please enter a password";
        }
        
        else if ($_GET['error'] == '4')
        {
          $error = "Please enter your FRC team number";
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

    <title>Registration - Scoutr v 2.0.0</title>

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
                        <h3 class="panel-title">Sign up now!</h3>
                    </div>
                    <div class="panel-body">
                    <?php if ($error != "") { ?>
                      <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Error!</strong> <?php print($error);?>
                      </div>
                    <?php } ?>
                        <form action="register.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Team Number" name="number" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-primary btn-block">
                                	Register
                                </button>
                                <a href="login.php" class="btn btn-block btn-default">Log In</a>
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