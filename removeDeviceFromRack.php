<?php
// handlers/addToRack.php
//
// handler to add a device to a rack
// $Id$
// $Source$

require_once('config/config.php');
require_once('inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

$query = "SELECT dr_pending_status FROM devices_rack WHERE dr_device_id=".((int)$_GET['device_id'])." AND dr_rack_id=".((int)$_GET['rack_id']).";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
$row = mysql_fetch_assoc($result);
if($row['dr_pending_status'] == 1)
{
    $query = "DELETE FROM devices_rack WHERE dr_device_id=".((int)$_GET['device_id'])." AND dr_rack_id=".((int)$_GET['rack_id']).";";
}
else
{
    $query = "UPDATE devices_rack SET dr_pending_status=2 WHERE dr_device_id=".((int)$_GET['device_id'])." AND dr_rack_id=".((int)$_GET['rack_id']).";";
}
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
if($result)
{
    header("Location: viewRack.php?rack_id=".$_GET['rack_id']);
}


?>