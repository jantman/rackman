<?php
// admin/vlanAdmin.php
//
// VLAN administration
// Time-stamp: "2009-02-15 01:31:31 jantman"
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
    $query = "INSERT INTO vlans SET vlan_num=".((int)$_POST['vlan_num']).",vlan_name='".mysql_real_escape_string($_POST['vlan_name'])."',vlan_desc='".mysql_real_escape_string($_POST['vlan_desc'])."';";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
}

// show the form
echo '<h2>VLANS:</h2>'."\n";

// show the existing locations
$query = "SELECT * FROM vlans ORDER BY vlan_num;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
while($row = mysql_fetch_assoc($result))
{
    echo 'VLAN '.$row['vlan_num'].' - '.$row['vlan_name'].' ('.$row['vlan_desc'].')<br />'."\n";
}
echo '<br />'."\n";
// end showing the locations

echo '<form name="vlanAdmin" method="post">'."\n";
echo '<input type="radio" name="action" id="action" value="add" checked="checked" /><label>Add VLAN:</label> <strong>Number:</strong> ';
echo '<input type="text" name="vlan_num" size="4" />';
echo ' <strong>Name: </strong><input type="text" name="vlan_name" size="20" /> <strong>Description:</strong><input type="text" name="vlan_desc" size="40" />'."\n";

echo '<div id="buttonDiv"><input type="submit" value="Submit"><input type="reset" value="Reset"></div>'."\n";
echo '</form>'."\n";
require_once('../footer.php');
?>

</body>

</html>
