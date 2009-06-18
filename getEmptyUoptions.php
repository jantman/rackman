<?php 
//
// getEmptyUoptions.php
//
// get the options for empty U spaces for a machine
//
// $Id$
// $Source$

$device_id=-1;

// get the URL variables
if(! empty($_GET['rack_id']))
{
    $rack_id = ((int)$_GET['rack_id']);
}
if(! empty($_GET['height']))
{
    $heightU = ((int)$_GET['height']);
}
if(! empty($_GET['device_id']))
{
    $device_id = ((int)$_GET['device_id']);
}

if(! empty($_GET['partheight']))
{
    $device_id = -1;
    $heightU = (int)$_GET['partheight'];
}

if(! isset($_GET['device_id']) && ! isset($_GET['height']) && ! isset($_GET['partheight']))
{
    die("&nbsp;");
}

require_once('config/config.php');
require_once('inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

if($device_id != -1)
{
    // get the height for this device
    $query = "SELECT device_height_U FROM devices WHERE device_id=".$device_id.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $row = mysql_fetch_assoc($result);
    $heightU = $row['device_height_U'];
}

echo '<select name="top_U_num" id="top_U_num">';
$rackSpaces = getRUtoHosts($rack_id);
for($i = count($rackSpaces); $i > 0; $i--)
{
    if($rackSpaces[$i] == -1)
    {
	if($heightU == 1)
	{
	    echo '<option value="'.$i.'">'.$i.'</option>';
	}
	else
	{
	    // make sure we have contiguous empty spaces
	    $contiguous = true;
	    for($x = $i; $x > ($i - $heightU); $x--)
	    {
		if($rackSpaces[$x] != -1 && $rackSpaces[$x] != $device_id){ $contiguous = false;}
	    }
	    if($contiguous)
	    {
		echo '<option value="'.$i.'">'.$i.' (to '.($x+1).')</option>';
	    }
	}
    }
}

if(! isset($_GET['height']))
{
    echo '<input type="submit" value="Add">';
}
echo '</select>';
?>
