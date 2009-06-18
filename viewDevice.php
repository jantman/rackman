<?php
// index.php
//
// main index page
// Time-stamp: "2009-02-28 01:36:22 jantman"
//
// Rack Management Project
// by Jason Antman <jason@jasonantman.com>
// $Id$
// $Source$
require_once('config/config.php');
require_once('inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");
if(! isset($_GET['id']))
{
    die("No ID specified.\n");
}

// TODO - need to do DHTML for interface patching like viewRack js:move() - add link pulls up DHTML form, on input, regen table. This means the interface table needs to be in its own DIV and needs a generate function.

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
<link rel="stylesheet" type="text/css" href="main.css" />
<script language="javascript" type="text/javascript" src="inc/rackDHTML.js"></script>
<script language="javascript" type="text/javascript" src="inc/grayout.js"></script>
</head>

<body>
<?php
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
echo '<tr><th>Status</th><td style="background-color: #'.$row['ods_color'].';">'.$device_row['ods_name'].'</td>'."\n";
echo '</table>'."\n";

echo '<h3>Interfaces</h3>'."\n";
// BEGIN add interface form
echo '<div class="addInterfaceForm">'."\n";
echo '<form name="addInterface" method="POST" action="handlers/addInterface.php">'."\n";
echo '<input type="hidden" name="device_id" id="device_id" value="'.$id.'" />'."\n";
echo '<strong>Add Interface to this Device - </strong> ';
echo '<strong>Type: </strong><select name="oit_id" id="oit_id">';
// show the available devices
echo '<option value="-1"> </option>';
$query = "SELECT * FROM opt_interface_types ORDER BY oit_type,oit_media,oit_standard;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
while($row = mysql_fetch_assoc($result))
{
    echo '<option value="'.$row['oit_id'].'">';
    $temp = "";
    if($row['oit_type'] != ""){$temp .= $row['oit_type']." / ";}
    if($row['oit_standard'] != ""){$temp .= $row['oit_standard']." / ";}
    if($row['oit_media'] != ""){$temp .= $row['oit_media']." / ";}
    if($row['oit_connector'] != ""){$temp .= $row['oit_connector']." / ";}
    if($row['oit_max_speed_bps'] != null){ $temp .= prettySpeed($row['oit_max_speed_bps']);}
    $temp = trim($temp, " /");
    echo $temp;
    echo '</option>';
}
// end showing available devices
echo '</select>';
echo ' <strong>Name:</strong> <input type="text" name="name" id="name" size="5" />';
echo ' <strong>MAC:</strong>  <input type="text" name="mac" id="mac" size="20" />';
echo ' <input type="submit" value="Submit"><input type="reset" value="Reset">'."\n";
echo '</form>'."\n";
echo '</div> <!-- End addDeviceForm DIV -->'."\n";
// END add interface form

require_once('inc/devices.php.inc');
echo '<div id="ifTableDiv">'."\n";
getInterfaceTable($id);
echo '</div> <!-- END ifTableDiv -->'."\n";

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
