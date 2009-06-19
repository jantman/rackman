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

$device_id=-1;

// get the URL variables
if(! empty($_GET['rack_id']))
{
    $rack_id = ((int)$_GET['rack_id']);
}
if(! empty($_GET['height']))
{
    $heightU = ((int)$_GET['height']);
}
if(! empty($_GET['device_id']))
{
    $device_id = ((int)$_GET['device_id']);
}

if(! empty($_GET['side']))
{
    $side = ((int)$_GET['side']);
}
else
{
    $side = 0;
}

if(! empty($_GET['partheight']))
{
    $device_id = -1;
    $heightU = (int)$_GET['partheight'];
}

if(! isset($_GET['device_id']) && ! isset($_GET['height']) && ! isset($_GET['partheight']))
{
    die("&nbsp;");
}

require_once('config/config.php');
require_once('inc/funcs.php.inc');
rackman_mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

error_log("getEmptyUoptions: rack_id=".$rack_id." heightU=".$heightU." device_id=".$device_id." partSide=".$side." partheight=".$partheight."\n"); // DEBUG

if($device_id != -1)
{
    // get the height for this device
    $query = "SELECT device_height_U FROM devices WHERE device_id=".$device_id.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $row = mysql_fetch_assoc($result);
    $heightU = $row['device_height_U'];
}

$rackSpaces = getRUtoHosts($rack_id);

error_log("getEmptyUoptions: heightU=".$heightU."\n"); // DEBUG
error_log($_SERVER["REQUEST_URI"]); // DEBUG

//echo '<pre>';
//echo var_dump($rackSpaces); // DEBUG
//echo '</pre>';

echo '<select name="top_U_num" id="top_U_num">';
for($i = count($rackSpaces[1]); $i > 0; $i--)
{

    $open = false;
    if($side == 0) {  if($rackSpaces[1][$i] == -1 && $rackSpaces[2][$i] == -1) { $open = true;}  }
    elseif($side == 1){  if($rackSpaces[1][$i] == -1) { $open = true;} }
    else{  if($rackSpaces[2][$i] == -1) { $open = true;} }

    if($open)
    {
	if($heightU == 1)
	{
	    echo '<option value="'.$i.'">'.$i.'</option>';
	}
	else
	{
	    echo "\ni=$i open=$open<br />\n"; // DEBUG
	    // make sure we have contiguous empty spaces
	    $contiguous = true;
	    for($x = $i; $x > ($i - $heightU); $x--)
	    {
		echo "\nside=$side x=$x rackSpaces[1][x]=".$rackSpaces[1][$x]." rackSpaces[2][x]=".$rackSpaces[2][$x]; // DEBUG
		if($side == 0)
		{
		    if(($rackSpaces[1][$x] != -1 && $rackSpaces[1][$x] != $device_id) || ($rackSpaces[2][$x] != -1 && $rackSpaces[2][$x] != $device_id)){ $contiguous = false;}
		}
		elseif($side == 1)
		{
		    if($rackSpaces[1][$x] != -1 && $rackSpaces[1][$x] != $device_id){ $contiguous = false;}
		}
		else
		{
		    if($rackSpaces[2][$x] != -1 && $rackSpaces[2][$x] != $device_id){ $contiguous = false;}
		}
	    }
	    if($contiguous)
	    {
		echo '<option value="'.$i.'">'.$i.' (to '.($x+1).')</option>';
	    }
	}
    }
}

echo '</select>';

if(! isset($_GET['height']))
{
    echo '  <input type="submit" value="Add">';
}

?>
