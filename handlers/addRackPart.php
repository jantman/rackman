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

$typeID = (int)$_POST['type_id'];
$rackID = (int)$_POST['rackID'];
$height = (int)$_POST['heightU'];
$topU = (int)$_POST['top_U_num'];
$statusID = (int)$_POST['statusID'];
$side = (int)$_POST['partSide'];

// get type name and class ID for this type
$query = "SELECT * FROM opt_device_types WHERE odt_id=".$typeID.";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
$row = mysql_fetch_assoc($result);
$typeName = $row['odt_name'];
$classID = $row['odt_class_id'];

$query = "INSERT INTO devices SET device_class_id=".$classID.",device_type_id=".$typeID.",device_name='".$typeName."',device_height_U=".$height.",device_status_id=".$statusID;
if($side != 0)
{
    $query .= ",device_depth_half_rack=1";
}
$query .= ";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
$deviceID = mysql_insert_id();

$query = "INSERT INTO devices_rack SET dr_device_id=".$deviceID.",dr_rack_id=".$rackID.",dr_top_U_num=".$topU.",dr_pending_status=1,dr_rack_side=".$side.";";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
if($result)
{
    header("Location: ../viewRack.php?rack_id=".$_POST['rackID']);
}


?>