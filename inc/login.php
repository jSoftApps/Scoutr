<?php

// Scoutr v 2.0.0
// Copyright (C) 2014 jSoft Apps, All Right Reserved
// This file uses the login form input to log a user in

// Connect to the database
include_once 'db_connect.php';
// Import our functions
include_once 'functions.php';
 
// Our custom secure way of starting a PHP session.
sec_session_start();
 
if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    // The hashed password.
    $password = $_POST['p'];
 
    if (login($email, $password, $mysqli) == true) {
        // Login success 
        header('Location: ../index.php');
    } else {
        // Login failed 
        header('Location: ../login.php?error=1');
    }
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}