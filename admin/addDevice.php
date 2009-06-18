<?php
// admin/addDevice.php
//
// script to add a device
// Time-stamp: "2009-02-18 14:12:26 jantman"
//
// Rack Management Project
// by Jason Antman <jason@jasonantman.com>
// $Id$
// $Source$
require_once('../config/config.php');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add Device - Rack Management</title>
<link rel="stylesheet" type="text/css" href="../main.css" />
<script language="javascript" type="text/javascript" src="../inc/rackDHTML.js"></script>
<script language="javascript" type="text/javascript" src="../inc/grayout.js"></script>
</head>

<body>
<h1>Add Device</h1>
<?php
if(isset($_POST) && isset($_POST['name']))
{
    // handle the form
    $classArray = explode("_", $_POST['class_type_os']);
    $query = "INSERT INTO devices SET device_class_id=".((int)$classArray[0]).",device_type_id=".((int)$classArray[1]);
    if(trim($classArray[2]) != "") { $query .= ",device_os_type_id=".((int)$classArray[2]); }
    $query .= ",device_name='".mysql_real_escape_string($_POST['name'])."'";
    if(trim($_POST['mfr']) != "") { $query .= ",device_mfr='".mysql_real_escape_string($_POST['mfr'])."'";}
    if(trim($_POST['model']) != "") { $query .= ",device_model='".mysql_real_escape_string($_POST['model'])."'";}
    $query .= ",device_height_U=".((int)$_POST['height_U']);
    if(trim($_POST['os_version']) != "") { $query .= ",device_os_version='".mysql_real_escape_string($_POST['os_version'])."'";}
    $query .= ",device_status_id=".((int)$_POST['status']).";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    if($result)
    {
	$devID = mysql_insert_id();
	echo '<h3>Device added with ID <a href="../viewDevice.php?id='.$devID.'">'.$devID.'</a></h3>'."\n";
    }
}
else
{
    echo '<form name="addDevice" method="post">'."\n";

    // name
    echo '<div><label for="name"><strong>Name:</label> </strong><input type="text" name="name" size="20" /></div>'."\n";

    // class/os/type
    // TODO: this should be 3 SELECTs with DHTML to dynamically change the options based on previous selections
    // this is a shameless hack
    echo '<div><label for="class_type_os"><strong>Device Class / Type / OS Type:</strong></label> <select name="class_type_os" id="class_type_os">';
    $query = "SELECT c.odc_id,c.odc_name,t.odt_id,t.odt_name,ot.odot_id,ot.odot_name FROM opt_device_classes AS c LEFT JOIN opt_device_types AS t ON c.odc_id=t.odt_class_id LEFT JOIN opt_device_os_types AS ot ON c.odc_id=ot.odot_class_id;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
	echo '<option value="'.$row['odc_id'].'_'.$row['odt_id'].'_'.$row['odot_id'].'">'.$row['odc_name'].' / '.$row['odt_name'].' / '.$row['odot_name'].'</option>';
    }
    echo '</select></div>'."\n";
    // end shameless hack

    // name
    // TODO - this should be a select for previously entered values OR a text entry
    echo '<div><label for="os_version"><strong>OS Version:</label> </strong><input type="text" name="os_version" size="20" /></div>'."\n";

    // manufacturer
    echo '<div><label for="mfr"><strong>Manufacturer:</label> </strong><input type="text" name="mfr" size="20" /></div>'."\n";
    // model
    echo '<div><label for="model"><strong>Model:</label> </strong><input type="text" name="model" size="20" /></div>'."\n";

    // height
    //echo '<div><label for="height_U"><strong>Height (U):</strong></label> <select name="height_U" id="height_U" onChange="updateUoptions()">';
    echo '<div><label for="height_U"><strong>Height (U):</strong></label> <select name="height_U" id="height_U">';
    echo '<option value="-1">&nbsp;&nbsp;</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option>';
    echo '</select></div>';

    // status
    echo '<div><label for="status"><strong>Status:</strong></label> <select name="status">';
    $query = "SELECT ods_id,ods_name FROM opt_device_statuses;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
	echo '<option value="'.$row['ods_id'].'">'.$row['ods_name'].'</option>';
    }
    echo '</select></div>'."\n";

    // rack
    // DON'T do this here - associate a device with a rack later.
    /*
    echo '<div><label for="rack"><strong>Location / Room / Rack:</strong></label> <select name="rack" id="rack">';
    $query = "SELECT ra.rack_id,ra.rack_identifier,ro.room_name,l.loc_name FROM racks AS ra LEFT JOIN rooms AS ro ON ra.rack_room_id=ro.room_id LEFT JOIN locations AS l ON ro.room_location_id=l.loc_id;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
	echo '<option value="'.$row['rack_id'].'">'.$row['loc_name'].' / '.$row['room_name'].' / '.$row['rack_identifier'].' ('.$row['rack_id'].')</option>';
    }
    echo '</select>';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    echo '<label for="top_U_num"><strong>Top U#:</strong></label> <span id="topUdiv"></span>';
    echo '</div>'."\n";
    */
    echo '<div id="buttonDiv"><input type="submit" value="Submit"><input type="reset" value="Reset"></div>'."\n";
    echo '</form>'."\n";
}
require_once('../footer.php');

?>

</body>

</html>
