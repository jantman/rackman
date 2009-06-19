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
require_once('../config/config.php');
require_once('../inc/funcs.php.inc');
rackman_mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");
if(! isset($_GET['id']) && ! isset($_POST['deviceID']))
{
    die("No ID specified.\n");
}

if(isset($_POST['deviceID']))
{
    // HANDLE THE FORM, send us back to the rack view

    // for now, we'll only update the statusID
    $query = "UPDATE devices set device_status_id=".((int)$_POST['statusID'])." WHERE device_id=".((int)$_POST['deviceID']).";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    if($result)
    {
	header("Location: ../viewRack.php?rack_id=".$_POST['rackID']);
    }
    die();
}
// ELSE DISPLAY THE FORM


$id = (int)$_GET['id'];
$query = "SELECT d.*,dr.*,dc.odc_name,dt.odt_name,odot.odot_name,ds.ods_name,ds.ods_color,r.rack_identifier FROM devices AS d LEFT JOIN devices_rack AS dr ON d.device_id=dr.dr_device_id LEFT JOIN opt_device_classes AS dc ON dc.odc_id=d.device_class_id LEFT JOIN opt_device_types AS dt ON dt.odt_id=d.device_type_id LEFT JOIN opt_device_os_types AS odot ON odot.odot_id=d.device_os_type_id LEFT JOIN opt_device_statuses AS ds ON d.device_status_id=ds.ods_id LEFT JOIN racks AS r ON dr.dr_rack_id=r.rack_id WHERE d.device_id=".$id.";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
if(mysql_num_rows($result) < 1){ die("Invalid ID.\n");}

$device_row = mysql_fetch_assoc($result);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $device_row['device_name']." - Rack Management";?></title>
<link rel="stylesheet" type="text/css" href="../main.css" />
<script language="javascript" type="text/javascript" src="../inc/rackDHTML.js"></script>
<script language="javascript" type="text/javascript" src="../inc/grayout.js"></script>
</head>

<body>
<form name="editDevice" method="POST" action="editDevice.php">
<?php
echo '<input type="hidden" name="rackID" value="'.$device_row['dr_rack_id'].'" />';
echo '<input type="hidden" name="deviceID" value="'.$id.'" />';

echo '<h1>'.$device_row['device_name']." - Rack Management".'</h1>'."\n";

echo '<table class="rackView">'."\n";
echo '<tr><th>Name</th><td>'.$device_row['device_name'].'</td>'."\n";
echo '<tr><th>Class</th><td>'.$device_row['odc_name'].'</td>'."\n";
echo '<tr><th>Type</th><td>'.$device_row['odt_name'].'</td>'."\n";
echo '<tr><th>Manufacturer</th><td>'.$device_row['device_mfr'].'</td>'."\n";
echo '<tr><th>Model</th><td>'.$device_row['device_model'].'</td>'."\n";
echo '<tr><th>Height (U)</th><td>'.$device_row['device_height_U'].'</td>'."\n";
echo '<tr><th>OS Type</th><td>'.$device_row['odot_name'].'</td>'."\n";
echo '<tr><th>OS Version</th><td>'.$device_row['device_os_version'].'&nbsp;</td>'."\n";
echo '<tr><th>Rack : Top U#</th><td><a href="viewRack.php?rack_id='.$device_row['dr_rack_id'].'">'.$device_row['rack_identifier'].'</a> : '.$device_row['dr_top_U_num'].'</td></tr>';
echo '<tr><th>Depth:</th><td>';
if($device_row['device_depth_half_rack'] == 1){ echo "Half Rack";} else { echo "Full Rack";}
echo '</td></tr>';

echo '<tr><th>Status</th><td>';
echo '<select name="statusID" id="statusID">';
$device_row['ods_name'];
$query = "SELECT * FROM opt_device_statuses;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
while($row = mysql_fetch_assoc($result))
{
    if($row['ods_name'] == $device_row['ods_name'])
    {
	echo '<option value="'.$row['ods_id'].'" selected="selected">'.$row['ods_name'].'</option>';
    }
    else
    {
	echo '<option value="'.$row['ods_id'].'">'.$row['ods_name'].'</option>';
    }
}
echo '</select>';
echo '</td>'."\n";

echo '</table>'."\n";

echo '<INPUT TYPE="SUBMIT" value="Submit">&nbsp;&nbsp;<INPUT TYPE="RESET">';


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
