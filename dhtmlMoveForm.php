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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="main.css" type="text/css">
<title>Move Host</title>
</head>
<body>
<form name="signon_form">

<?php 
$hiddenItems = "";

// get the URL variables
if(! empty($_GET['id']))
{
    $id = ((int)$_GET['id']);
    $hiddenItems .= '<input name="id" type="hidden" value="'.$id.'" id="id" />';
}

require_once('config/config.php');
require_once('inc/funcs.php.inc');
rackman_mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

// get device info
$query = "SELECT d.device_name,d.device_height_U,dr.dr_rack_id,dr.dr_top_U_num FROM devices AS d LEFT JOIN devices_rack AS dr ON d.device_id=dr.dr_device_id WHERE device_id=".$id.";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
if(mysql_num_rows($result) < 1)
{
    echo '<div class="errorDiv">ERROR: No host found with ID=='.$id.'.</div>';
    die();
}
$row = mysql_fetch_assoc($result);
$heightU = $row['device_height_U'];

echo '<div>'."\n";
echo $hiddenItems."\n";
echo '<table border="0">'."\n\n";
echo '	<tr>'."\n";
echo '		<td style="white-space: nowrap; background-color: #CCCCCC;" align="left" valign="top" colspan="2"><b>Move Host '.$row['device_name'].'</b></td>'."\n".'	</tr>'."\n";

echo '	<tr>
		<td align="right" valign="top"><b>Current Top Position:</b></td>
		<td valign="top" align="left">';
if($heightU > 1)
{
    echo $row['dr_top_U_num'].' (to '.($row['dr_top_U_num'] - ($heightU-1)).')';
}
else
{
    echo $row['dr_top_U_num'];
}
echo '</td>
	</tr>
	<tr>
		<td align="right" valign="top"><b>Height:</b></td>
		<td valign="top" align="left">'.$heightU.'</td>
	</tr>'."\n";

echo'	<tr>
		<td align="right" valign="top"><b>New Top Position:</b></td>'."\n";
echo '          <td valign="top" align="left">';
echo '<select name="newTopU" id="newTopU">';
$rackSpaces = getRUtoHosts($row['dr_rack_id']);
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
		if($rackSpaces[$x] != -1 && $rackSpaces[$x] != $id){ $contiguous = false;}
	    }
	    if($contiguous)
	    {
		echo '<option value="'.$i.'">'.$i.' (to '.($x+1).')</option>';
	    }
	}
    }
}
echo '</select>';
echo '</td>'."\n";
echo '	</tr>'."\n";

// BUTTONS
echo '<tr><td valign="top" align="center" colspan="2"><input name="buttonGroup[btnCancel]" value="Cancel" onClick="hidePopup(\'popup\')" type="button" />    <input name="buttonGroup[btnSubmit]" value="Submit" type="button" onClick="submitForm()" />    </td></tr>'."\n";
echo '</table> </div>'."\n";

?>
</form>
</body>
</html>