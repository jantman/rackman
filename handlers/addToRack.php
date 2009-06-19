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

$query = "INSERT INTO devices_rack SET dr_device_id=".((int)$_POST['device_id']).",dr_rack_id=".((int)$_POST['rackID']).",dr_top_U_num=".((int)$_POST['top_U_num']).",dr_pending_status=1,dr_rack_side=".((int)$_POST['deviceSide']).";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
if($result)
{
    header("Location: ../viewRack.php?rack_id=".$_POST['rackID']);
}


?>