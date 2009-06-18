<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="main.css" type="text/css">
<title>Patch Interface</title>
</head>
<body>
<form name="signon_form">

<?php 
//
// dhtmlPatchForm.php
//
// create a patch between interfaces
//
// $Id$
// $Source$

// get the URL variables
if(! empty($_GET['id']))
{
    $id = ((int)$_GET['id']);
    $hiddenItems .= '<input name="id" type="hidden" value="'.$id.'" id="id" />';
}

require_once('config/config.php');
require_once('inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

// get interface
$query = "SELECT di.di_pkey,di.di_name,di.di_type,di.di_alias,d.device_name FROM device_interfaces AS di LEFT JOIN devices AS d ON d.device_id=di.di_device_id WHERE di.di_pkey=".$id.";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
if(mysql_num_rows($result) < 1)
{
    echo '<div class="errorDiv">ERROR: No interface found with ID=='.$id.'.</div>';
    die();
}
$row = mysql_fetch_assoc($result);

echo '<div>'."\n";
echo $hiddenItems."\n";
echo '<table border="0">'."\n\n";
echo '	<tr>'."\n";
echo '		<td style="white-space: nowrap; background-color: #CCCCCC;" align="left" valign="top" colspan="2"><b>Add Patch to '.$row['device_name'].' '.$row['di_name'].'</b></td>'."\n".'	</tr>'."\n";

echo'	<tr>
		<td align="right" valign="top"><b>Patch To:</b></td>'."\n";
echo '          <td valign="top" align="left">';
echo '<select name="patchToID" id="patchToID">';
$query = "SELECT di.di_pkey,di.di_name,di.di_type,di.di_alias,d.device_name FROM device_interfaces AS di LEFT JOIN devices AS d ON d.device_id=di.di_device_id WHERE di.di_pkey != ".$id." ORDER BY d.device_name,di.di_IF_MIB_ifindex,di.di_name;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
while($row = mysql_fetch_assoc($result))
{
    echo '<option value="'.$row['di_pkey'].'">'.$row['device_name'].' '.$row['di_name'];
    if(trim($row['di_alias']) != ""){ echo ' ('.$row['di_alias'].')';}
    echo '</option>';
}
echo '</select>';
echo '</td>'."\n";
echo '	</tr>'."\n";

// BUTTONS
echo '<tr><td valign="top" align="center" colspan="2"><input name="buttonGroup[btnCancel]" value="Cancel" onClick="hidePopup(\'popup\')" type="button" />    <input name="buttonGroup[btnSubmit]" value="Submit" type="button" onClick="submitPatchForm()" />    </td></tr>'."\n";
echo '</table> </div>'."\n";

?>
</form>
</body>
</html>