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

$query = "INSERT INTO device_interfaces SET di_device_id=".((int)$_POST['device_id']).",di_type=".((int)$_POST['oit_id']).",di_name='".mysql_real_escape_string($_POST['name'])."',di_mac_address='".do_mac($_POST['mac'])."';";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
if($result)
{
    header("Location: ../viewDevice.php?id=".$_POST['device_id']);
}

function do_mac($s)
{
    $s = str_replace(":", "", $s);
    $s = str_replace("-", "", $s);
    $s = strtoupper($s);
    $s = mysql_real_escape_string($s);
    return $s;
}

?>