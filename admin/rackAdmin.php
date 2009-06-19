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
    $query = "INSERT INTO racks SET rack_room_id=".((int)$_POST['rack_room_id']).",rack_identifier='".mysql_real_escape_string($_POST['rack_identifier'])."',rack_description='".mysql_real_escape_string($_POST['rack_desc'])."',rack_height_U=".((int)$_POST['rack_height_U']).",rack_type_id=".((int)$_POST['rack_type_id']).";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
}

// show the form
echo '<h2>Racks:</h2>'."\n";

// show the existing locations
$query = "SELECT * FROM racks ORDER BY rack_room_id;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
while($row = mysql_fetch_assoc($result))
{
    echo 'Rack '.$row['rack_id'].' Room '.$row['rack_room_id'].' - '.$row['rack_identifier'].' ('.$row['rack_description'].') - '.$row['rack_height_U'].'U<br />'."\n";
}
echo '<br />'."\n";
// end showing the locations

echo '<form name="rackAdmin" method="post">'."\n";
echo '<input type="radio" name="action" id="action" value="add" checked="checked" /><label>Add Rack:</label> <strong>Room:</strong> ';
echo '<select name="rack_room_id">';
$query = "SELECT room_id,room_name FROM rooms ORDER BY room_name ASC;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
while($row = mysql_fetch_assoc($result))
{
    echo '<option value="'.$row['room_id'].'">'.$row['room_name'].'</option>';
}
echo '</select>'."\n";
echo ' <strong>Identifier: </strong><input type="text" name="rack_identifier" size="20" /> <strong>Description:</strong><input type="text" name="rack_desc" size="40" /><br />'."\n";
echo ' <strong>Height (U):</strong> <input type="text" name="rack_height_U" size="2" /> <strong>Type:</strong> ';
echo '<select name="rack_type_id">';
$query = "SELECT ort_id,ort_name FROM opt_rack_types ORDER BY ort_name ASC;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
while($row = mysql_fetch_assoc($result))
{
    echo '<option value="'.$row['ort_id'].'">'.$row['ort_name'].'</option>';
}
echo '</select>'."\n";


echo '<div id="buttonDiv"><input type="submit" value="Submit"><input type="reset" value="Reset"></div>'."\n";
echo '</form>'."\n";
require_once('../footer.php');
?>

</body>

</html>
