<?php

// Scoutr v 2.0.0
// Copyright (C) 2014 jSoft Apps, All Right Reserved
// This file logs out the user and destroys the session

// Include our functions
include_once 'functions.php';
sec_session_start();
 
// Unset all session values 
$_SESSION = array();
 
// get session parameters 
$params = session_get_cookie_params();
 
// Delete the actual cookie. 
setcookie(session_name(),
        '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]);
 
// Destroy session 
session_destroy();
header('Location: ../login.php?message=1');