<?php

// Scoutr v 2.0.0
// Copyright (C) 2014 jSoft Apps, All Right Reserved
// This stores database connection information
// Some of the information below will need to be
// Modified in order for Scoutr to work on your server

// The database host, usually 127.0.0.1
define("HOST", "localhost");
// User that connects to the database
define("USER", "root");
// Database user's password
define("PASSWORD", "");
// The name of the database itself
define("DATABASE", "scoutr"); 
 
// Determine if unregistered users can create accounts (should be "any"
define("CAN_REGISTER", "any");
// Sets the role of newly registered users
define("DEFAULT_ROLE", "member");
 
// FOR DEVELOPMENT ONLY. On a production server, this
// Should be set to TRUE. This enables HTTPS
define("SECURE", FALSE);