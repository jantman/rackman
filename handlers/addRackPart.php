<?php
// handlers/addToRack.php
//
// handler to add a device to a rack
// $Id$
// $Source$

require_once('../config/config.php');
require_once('../inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

$typeID = (int)$_POST['type_id'];
$rackID = (int)$_POST['rackID'];
$height = (int)$_POST['heightU'];
$topU = (int)$_POST['top_U_num'];
$statusID = (int)$_POST['statusID'];

// get type name and class ID for this type
$query = "SELECT * FROM opt_device_types WHERE odt_id=".$typeID.";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
$row = mysql_fetch_assoc($result);
$typeName = $row['odt_name'];
$classID = $row['odt_class_id'];

$query = "INSERT INTO devices SET device_class_id=".$classID.",device_type_id=".$typeID.",device_name='".$typeName."',device_height_U=".$height.",device_status_id=".$statusID.";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
$deviceID = mysql_insert_id();

$query = "INSERT INTO devices_rack SET dr_device_id=".$deviceID.",dr_rack_id=".$rackID.",dr_top_U_num=".$topU.",dr_pending_status=1;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
if($result)
{
    header("Location: ../viewRack.php?rack_id=".$_POST['rackID']);
}


?>