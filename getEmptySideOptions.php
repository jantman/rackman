<?php 
//
// getEmptySideOptions.php
//
// figure out whether a device is full depth or half
//
// $Id$
// $Source$

$device_id=-1;
$rack_id = -1;

if(! empty($_GET['device_id']))
{
    $device_id = ((int)$_GET['device_id']);
}

if(! empty($_GET['rack_id']))
{
    $rack_id = ((int)$_GET['rack_id']);
}

if(! isset($_GET['device_id']))
{
    die("&nbsp;");
}

//error_log($_SERVER["REQUEST_URI"]); // DEBUG

require_once('config/config.php');
require_once('inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

if($device_id != -1)
{
    // get the height for this device
    $query = "SELECT device_depth_half_rack FROM devices WHERE device_id=".$device_id.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $row = mysql_fetch_assoc($result);
    $depth = $row['device_depth_half_rack'];
}

echo '<select name="deviceSide" id="deviceSide" onChange="javascript:updateUoptions('.$rack_id.')">';
if($depth == 1)
{
    echo '<option value="1">Front</option><option value="2">Rear</option>';
}
else
{
    echo '<option value="0">Full Depth</option>';
}
echo '</select>';

?>
