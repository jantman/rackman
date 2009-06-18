<?php
//
// handlers/moveDevice.php
//
// handler for device move popup form
// Time-stamp: "2009-03-05 20:52:29 jantman"
// $Id$
// $Source$

if(isset($_POST['id']))
{
    $id = (int)$_POST['id'];
}
else
{
    die("ERROR: No hostID specified.\n");
}

if(isset($_POST['newTopU']))
{
    $newTopU = (int)$_POST['newTopU'];
}
else
{
    die("ERROR: No new top U number specified.\n");
}

require_once('../config/config.php');
require_once('../inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

// get the rackID
$query = "SELECT dr_rack_id FROM devices_rack WHERE dr_device_id=".$id.";";
$result = mysql_query($query) or die("ERROR: Error in query: ".$query."\nError: ".mysql_error());
$row = mysql_fetch_assoc($result);
$rackID = $row['dr_rack_id'];

// set a removed pending_status
$query = "UPDATE devices_rack SET dr_pending_status=2 WHERE dr_device_id=".$id.";";
$result = mysql_query($query) or die("ERROR: Error in query: ".$query."\nError: ".mysql_error());

$query = "INSERT INTO devices_rack SET dr_pending_status=1,dr_device_id=".$id.",dr_rack_id=".$rackID.",dr_top_U_num=".$newTopU.";";
$result = mysql_query($query) or die("ERROR: Error in query: ".$query."\nError: ".mysql_error());


?>