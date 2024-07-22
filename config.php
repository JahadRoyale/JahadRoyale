<?php
$config['mysql']['host'] = "34.138.219.75";  // MySQL Server IP/Hostname
$config['mysql']['user'] = "root";     // MySQL Username
$config['mysql']['pass'] = "12123790";   // MySQL Password
$config['mysql']['name'] = "meh";        // MySQL Database name

// Create connection
$connection = mysqli_connect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['pass'], $config['mysql']['name']);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";
?>
