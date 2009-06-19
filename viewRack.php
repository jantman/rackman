<?php
//
// +----------------------------------------------------------------------+
// | RackMan      http://rackman.jasonantman.com                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2009 Jason Antman.                                     |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 3 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to:                           |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | ADDITIONAL TERMS (pursuant to GPL Section 7):                        |
// | 1) You may not remove any of the "Author" or "Copyright" attributions|
// |     from this file or any others distributed with this software.     |
// | 2) If modified, you must make substantial effort to differentiate    |
// |     your modified version from the original, while retaining all     |
// |     attribution to the original project and authors.                 |
// +----------------------------------------------------------------------+
// |Please use the above URL for bug reports and feature/support requests.|
// +----------------------------------------------------------------------+
// | Authors: Jason Antman <jason@jasonantman.com>                        |
// +----------------------------------------------------------------------+
// | $LastChangedRevision::                                             $ |
// | $HeadURL::                                                         $ |
// +----------------------------------------------------------------------+
require_once('config/config.php');
require_once('inc/funcs.php.inc');
rackman_mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>View Rack - Rack Management</title>
<link rel="stylesheet" type="text/css" href="main.css" />
<script language="javascript" type="text/javascript" src="inc/rackDHTML.js"></script>
<script language="javascript" type="text/javascript" src="inc/grayout.js"></script>
</head>

<body>
<?php
if(isset($_GET['rack_id']))
{
    $id = (int)$_GET['rack_id'];

    echo '<input type="hidden" id="rackID" value="'.$id.'" />'."\n";
    echo '<div class="rackView">'."\n";

    // show the one rack
    $query = "SELECT ra.rack_id,ra.rack_identifier,ra.rack_height_U,ro.room_name,l.loc_name FROM racks AS ra LEFT JOIN rooms AS ro ON ra.rack_room_id=ro.room_id LEFT JOIN locations AS l ON ro.room_location_id=l.loc_id WHERE ra.rack_id=".$id.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $row = mysql_fetch_assoc($result);
    echo '<h1><span style="background-color: white; padding: 2px; border: 1px solid black;">'.$row['loc_name'].' / '.$row['room_name'].' / '.$row['rack_identifier'].' ('.$row['rack_id'].')</span></h1>';
    $rack_height = $row['rack_height_U'];

    // BEGIN add Device form
    echo '<div class="addDeviceForm">'."\n";
    echo '<form name="addDevice" method="POST" action="handlers/addToRack.php">'."\n";
    echo '<input type="hidden" name="rackID" id="rackID" value="'.$id.'" />'."\n";
    echo '<strong>Add Device to this Rack:</strong> ';
    echo '<select name="device_id" id="device_id" onChange="javascript:updateSideOptions('.$id.')">';
    // show the available devices
    echo '<option value="-1"> </option>';
    $query = "SELECT d.device_id,d.device_name,d.device_height_U,d.device_mfr,d.device_model FROM devices AS d LEFT JOIN devices_rack AS dr ON d.device_id=dr.dr_device_id WHERE dr.dr_rack_id IS NULL;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
	echo '<option value="'.$row['device_id'].'">'.$row['device_name'].' ('.$row['device_height_U'].'U) ('.$row['device_mfr'].' '.$row['device_model'].')</option>';
    }
    // end showing available devices
    echo '</select>';
    // select for side
    // TODO - This needs to be validated for the device...
    echo ' <strong>Side: </strong><span id="deviceSideSpan"></span>';
    // end select for side
    echo ' <strong>Top U:</strong> <span id="topUspan"></span>';
    echo ' <input type="reset" value="Reset" />';
    echo '</form>'."\n";
    echo '</div> <!-- End addDeviceForm DIV -->'."\n";
    // END add device form

    // BEGIN add rack part form
    echo '<div class="addRackPartForm">'."\n";
    echo '<form name="addRackPart" method="POST" action="handlers/addRackPart.php">'."\n";
    echo '<input type="hidden" name="rackID" value="'.$id.'" />'."\n";
    echo '<strong>Add Rack Part to this Rack:</strong> ';
    echo '<select name="type_id" id="type_id">';
    // show the part options
    echo '<option value="-1"> </option>';
    $query = "SELECT o.odt_id,o.odt_name FROM opt_device_types AS o LEFT JOIN opt_device_classes AS c ON o.odt_class_id=c.odc_id WHERE c.odc_name='RackParts' ORDER BY o.odt_name;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
	echo '<option value="'.$row['odt_id'].'">'.$row['odt_name'].'</option>';
    }
    echo '</select>'."\n";
    // select for status
    echo '<strong>Status:</strong> <select name="statusID" id="statusID">';
    $query = "SELECT * FROM opt_device_statuses;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
	echo '<option value="'.$row['ods_id'].'">'.$row['ods_name'].'</option>';
    }
    echo '</select>';
    // end select for status
    // select for side
    echo ' <strong>Side: </strong><select name="partSide" id="partSide"><option value="0">Full Depth</option><option value="1" selected="selected">Front</option><option value="2">Rear</option></select>';
    // end select for side
    // select for height
    echo '<strong>Height: </strong><select name="heightU" id="heightU" onChange="javascript:updateRackPart('.$id.')"><strong>U</strong>'."\n";
    echo '<option value="0"> </option>';
    for($i = 1; $i < 11; $i++){ echo '<option value="'.$i.'">'.$i.'</option>';}
    echo '</select>'."\n";
    // end select for height
    echo ' <strong>Top U:</strong> <span id="rackPartTopUspan"></span>'; // span will be updated via JS/DHTML
    echo '<INPUT TYPE="RESET">'."\n";
    echo '</form>'."\n";
    echo '</div> <!-- End addRackPartForm DIV -->'."\n";
    // END add rack part form

    // BEGIN commit/rollback form
    echo '<div class="commitForm" id="commitForm">'."\n";
    $query = "SELECT dr_device_id FROM devices_rack WHERE dr_pending_status!=0;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    if(mysql_num_rows($result) > 0)
    {
	echo '<form name="commitForm" method="POST" action="handlers/commitRack.php">'."\n";
	echo '<input type="hidden" name="rackID" value="'.$id.'" />'."\n";
	echo '<input type="Submit" name="submit" value="Commit">'."\n";
	echo '<input type="Submit" name="submit" value="Rollback">'."\n";
	echo '</form>'."\n";
    }
    echo '</div> <!-- End commitForm DIV -->'."\n";
    // END commit/rollback form

    // show status colors
    $statusColors = array();
    $query = "SELECT * FROM opt_device_statuses;";
    $myresult = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $statusTable = '<table class="rackTable"><tr>';
    while($myrow = mysql_fetch_assoc($myresult))
    {
	$statusTable .= '<td style="background-color: #'.$myrow['ods_color'].';">'.$myrow['ods_name'].'</td>';
	$statusColors[$myrow['ods_id']] = $myrow['ods_color'];
    }
    $statusTable .= '<td>** Pending/Uncommitted</td>';
    $statusTable .= '</tr></table><br />'."\n";
    // end status colors

    $hosts = getHosts($id);
    $rackUnits = getRUtoHosts($id);

    require_once('inc/rackTable.php.inc');
    // output the rack HTML table
    echo '<div id="rackTableDiv">'."\n";
    showRackTable($id);
    echo '</div> <!-- end rackTableDiv DIV -->'."\n".'</div> <!-- END rackView DIV -->'."\n";

    echo '<br />'.$statusTable."\n";
}
else
{
    // show a list of racks
    echo '<h1>Racks</h1>'."\n";
    echo '<p><ul>'."\n";
    $query = "SELECT ra.rack_id,ra.rack_identifier,ro.room_name,l.loc_name FROM racks AS ra LEFT JOIN rooms AS ro ON ra.rack_room_id=ro.room_id LEFT JOIN locations AS l ON ro.room_location_id=l.loc_id;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
	echo '<li><a href="viewRack.php?rack_id='.$row['rack_id'].'">'.$row['loc_name'].' / '.$row['room_name'].' / '.$row['rack_identifier'].' ('.$row['rack_id'].')</a></li>';
    }
    echo '</ul></p>'."\n";
}

require_once('footer.php');

?>

<div id="popup" class="popup">
<div id="popuptitleArea">
<div id="popuptitle"></div>

<div id="popupCloseBox" onClick="hidePopup()">X</div>
<div id="clearing"></div>
</div> <!-- END popuptitleArea DIV -->
<div id="popupbody">
</div> <!-- END popupbody DIV -->
</div> <!-- END popup DIV -->


</body>

</html>
