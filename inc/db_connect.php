<?php

// Scoutr v 2.0.0
// Copyright (C) 2014 jSoft Apps, All Right Reserved
// This file connects Scoutr to the database
// This file should not need to be modified.

// Import the connection information
include_once 'config.database.php';
// Connect to the database
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);