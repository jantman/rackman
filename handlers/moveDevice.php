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

if(isset($_POST['id']))
{
    $id = (int)$_POST['id'];
}
else
{
    die("ERROR: No hostID specified.\n");
}

if(isset($_POST['newTopU']))
{
    $newTopU = (int)$_POST['newTopU'];
}
else
{
    die("ERROR: No new top U number specified.\n");
}

require_once('../config/config.php');
require_once('../inc/funcs.php.inc');
rackman_mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

// get the rackID
$query = "SELECT dr_rack_id FROM devices_rack WHERE dr_device_id=".$id.";";
$result = mysql_query($query) or die("ERROR: Error in query: ".$query."\nError: ".mysql_error());
$row = mysql_fetch_assoc($result);
$rackID = $row['dr_rack_id'];

// set a removed pending_status
$query = "UPDATE devices_rack SET dr_pending_status=2 WHERE dr_device_id=".$id.";";
$result = mysql_query($query) or die("ERROR: Error in query: ".$query."\nError: ".mysql_error());

$query = "INSERT INTO devices_rack SET dr_pending_status=1,dr_device_id=".$id.",dr_rack_id=".$rackID.",dr_top_U_num=".$newTopU.";";
$result = mysql_query($query) or die("ERROR: Error in query: ".$query."\nError: ".mysql_error());


?>