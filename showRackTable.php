<?php
// index.php
//
// main index page
// Time-stamp: "2009-06-18 19:07:04 jantman"
//
// Rack Management Project
// by Jason Antman <jason@jasonantman.com>
// $Id$
// $Source$
require_once('config/config.php');
require_once('inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

if(isset($_GET['rack_id']))
{
    $id = (int)$_GET['rack_id'];

    // show the one rack
    $query = "SELECT ra.rack_id,ra.rack_identifier,ra.rack_height_U,ra.rack_two_sided,ro.room_name,l.loc_name FROM racks AS ra LEFT JOIN rooms AS ro ON ra.rack_room_id=ro.room_id LEFT JOIN locations AS l ON ro.room_location_id=l.loc_id WHERE ra.rack_id=".$id.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $row = mysql_fetch_assoc($result);
    $rack_height = $row['rack_height_U'];
    if($row['rack_two_sided'] == 1){ $isTwoSided = true;} else { $isTwoSided = false;}

    $hosts = getHosts($id);
    $rackUnits = getRUtoHosts($id);

    // show status colors
    $statusColors = array();
    $query = "SELECT * FROM opt_device_statuses;";
    $myresult = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    while($myrow = mysql_fetch_assoc($myresult))
    {
	$statusColors[$myrow['ods_id']] = $myrow['ods_color'];
    }
    // end status colors


    // output the rack HTML table
    echo '<table class="rackTable">'."\n";
    if($isTwoSided) { echo '<tr><th>&nbsp;</th><th>Front</th><th>Rear</th></tr>'."\n";}
    $lastHost = -1;
    for($i = $rack_height; $i > 0; $i--)
    {
	if($rackUnits[$i] != -1 && $rackUnits[$i] != $lastHost)
	{
	    echo '<tr><th>'.$i.'</th>';
	    $hostID = $rackUnits[$i];
	    if($hosts[$hostID]['height_U'] > 1) { echo '<td rowspan="'.$hosts[$hostID]['height_U'].'" style="background-color: #'.$statusColors[$hosts[$hostID]['status_id']].';">';} else{ echo '<td style="background-color: #'.$statusColors[$hosts[$hostID]['status_id']].';">';}
	    echo '<a href="viewDevice.php?id='.$hostID.'">'.$hosts[$hostID]['name'].'</a>'.'&nbsp;&nbsp;&nbsp;('.$hosts[$hostID]['mfr'].' '.$hosts[$hostID]['model'].') (<a href="editDevice.php?id='.$hostID.'">e</a> <a href="removeDeviceFromRack.php?id='.$hostID.'">r</a> <a href="javascript:showMoveForm('.$hostID.')">m</a>)';
	    echo '</td></tr>'."\n";
	    $lastHost = $hostID;
	}
	elseif($rackUnits[$i] != -1 && $rackUnits[$i] == $lastHost)
	{
	    echo '<tr><th>'.$i.'</th></tr>'."\n";
	}
	else
	{
	    // open space
	    echo '<tr><th>'.$i.'</th><td style="background-color: #CCCCCC;">&nbsp;</td></tr>'."\n";
	}
    }

    echo '</table>'."\n";

}

?>

