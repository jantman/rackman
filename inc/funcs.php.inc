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

function getHosts($rackID)
{
    $query = "SELECT dr.dr_device_id,dr.dr_top_U_num,dr.dr_pending_status,d.device_id,d.device_name,d.device_mfr,d.device_model,d.device_height_U,d.device_status_id,d.device_depth_half_rack,s.ods_name FROM devices_rack AS dr LEFT JOIN devices AS d ON d.device_id=dr.dr_device_id LEFT JOIN opt_device_statuses AS s ON d.device_status_id=s.ods_id WHERE dr.dr_rack_id=".$rackID." ORDER BY dr.dr_top_U_num DESC;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    
    $hosts = array(); // array to hold the host info

    // populate arrays with all of the info
    while($row = mysql_fetch_assoc($result))
    {
	$temp = array("name" => $row['device_name'], "mfr" => $row['device_mfr'], "model" => $row['device_model'], "height_U" => $row['device_height_U'], "status" => $row['ods_name'], "status_id" => $row['device_status_id'], "pending" => $row['dr_pending_status'], "depth" => $row['device_depth_half_rack']);
	// 	$rackUnits[$row['dr_top_U_num']] = $row['dr_device_id'];
	$hosts[$row['device_id']] = $temp;
    }
    return $hosts;
}

// returns an array of rack U spaces to Host IDs, -1 if empty
function getRUtoHosts($rackID)
{
    $rackUnits = array(1 => array(), 2 => array()); // array to hold what's in each of the rack units

    // get the rack height
    $query = "SELECT ra.rack_id,ra.rack_height_U FROM racks AS ra WHERE ra.rack_id=".$rackID.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $row = mysql_fetch_assoc($result);
    $rack_height = $row['rack_height_U'];

    // populate the array with placeholder entries for each U
    for($i = $rack_height; $i > 0; $i--)
    {
	$rackUnits[1][$i] = -1; // placeholder
	$rackUnits[2][$i] = -1; // placeholder
    }

    $query = "SELECT dr.dr_device_id,dr.dr_top_U_num,d.device_height_U,dr.dr_rack_side FROM devices_rack AS dr LEFT JOIN devices AS d ON d.device_id=dr.dr_device_id WHERE dr.dr_rack_id=".$rackID." AND (dr_pending_status=0 OR dr_pending_status=1) ORDER BY dr.dr_top_U_num DESC;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    
    // populate arrays with all of the info
    while($row = mysql_fetch_assoc($result))
    {
	for($i = $row['dr_top_U_num']; $i > ($row['dr_top_U_num'] - $row['device_height_U']); $i--)
	{
	    if($row['dr_rack_side'] == 0){ $rackUnits[1][$i] = $row['dr_device_id']; $rackUnits[2][$i] = $row['dr_device_id']; }
	    elseif($row['dr_rack_side'] == 1) { $rackUnits[1][$i] = $row['dr_device_id']; }
	    elseif($row['dr_rack_side'] == 2) { $rackUnits[2][$i] = $row['dr_device_id']; }
	}
    }
    return $rackUnits;
}

function prettySpeed($s)
{
    $temp = "";
    if($s % 1000000000 == 0){ $temp .= ($s / 1000000000).' Gbps';}
    elseif($s % 1000000 == 0){ $temp .= ($s / 1000000).' Mbps';}
    elseif($s % 1000 == 0){ $temp .= ($s / 1000).' Kbps';}
    else{ $temp .= $s.' bps';}
    return $temp;
}

function formatMAC($s)
{
    $mac = "";
    $mac .= substr($s, 0, 2)."-";
    $mac .= substr($s, 2, 2)."-";
    $mac .= substr($s, 4, 2)."-";
    $mac .= substr($s, 6, 2)."-";
    $mac .= substr($s, 8, 2)."-";
    $mac .= substr($s, 10, 2);
    return $mac;
}

function rackman_mysql_connect()
{
    if(isset($CONFIG_dbUser))
    {
	$conn = mysql_connect("localhost", $CONFIG_dbUser);
    }
    else
    {
	$conn = mysql_connect();
    }
    return $conn;
}

?>