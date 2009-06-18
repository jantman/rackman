<?php
// admin/roomAdmin.php
//
// rooms administration
// Time-stamp: "2009-02-15 01:31:25 jantman"
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
<title>Room Admin - Rack Management</title>
<link rel="stylesheet" type="text/css" href="../main.css" />
</head>

<body>

<?php
if(isset($_POST['action']))
{
    // handle the form
    $query = "INSERT INTO rooms SET room_name='".mysql_real_escape_string($_POST['room_name'])."',room_desc='".mysql_real_escape_string($_POST['room_desc'])."',room_location_id=".((int)$_POST['room_location_id']).";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
}

// show the form
echo '<h2>Rooms:</h2>'."\n";

// show the existing locations
$query = "SELECT * FROM rooms ORDER BY room_location_id,room_id;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
while($row = mysql_fetch_assoc($result))
{
    echo 'Location '.$row['room_location_id'].' Room '.$row['room_id'].' - '.$row['room_name'].' ('.$row['room_desc'].')<br />'."\n";
}
echo '<br />'."\n";
// end showing the locations

echo '<form name="roomAdmin" method="post">'."\n";
echo '<input type="radio" name="action" id="action" value="add" checked="checked" /><label>Add Room:</label> <strong>Location:</strong> ';
echo '<select name="room_location_id">';
$query = "SELECT loc_id,loc_name FROM locations ORDER BY loc_name ASC;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
while($row = mysql_fetch_assoc($result))
{
    echo '<option value="'.$row['loc_id'].'">'.$row['loc_name'].'</option>';
}
echo '</select>'."\n";
echo ' <strong>Name: </strong><input type="text" name="room_name" size="20" /> <strong>Description:</strong><input type="text" name="room_desc" size="40" />'."\n";


echo '<div id="buttonDiv"><input type="submit" value="Submit"><input type="reset" value="Reset"></div>'."\n";
echo '</form>'."\n";
require_once('../footer.php');
?>

</body>

</html>
