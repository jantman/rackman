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

function getInterfaceTable($id)
{
// show interfaces here
    $query = "SELECT di1.di_pkey AS di1_pkey,di1.di_name,di1.di_type,di1.di_mac_address,di1.di_hostname,di1.di_ip_address,di1.di_alias,oit.oit_type,oit.oit_media,oit.oit_max_speed_bps,oit.oit_connector,oit.oit_standard,ip.ip_if1_pkey,ip.ip_if2_pkey,di2.di_pkey AS di2_pkey,di2.di_name AS di2_name,di2.di_alias AS di2_alias,d.device_name AS device2_name,d.device_id AS device2_id FROM device_interfaces AS di1 LEFT JOIN opt_interface_types AS oit ON di1.di_type=oit.oit_id LEFT JOIN interface_patches AS ip ON ip.ip_if1_pkey=di1.di_pkey LEFT JOIN device_interfaces AS di2 ON ip.ip_if2_pkey=di2.di_pkey LEFT JOIN devices AS d ON di2.di_device_id=d.device_id WHERE di1.di_device_id=".$id." ORDER BY di1.di_IF_MIB_ifindex ASC;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    echo '<table class="rackView">'."\n";
    echo '<tr><th rowspan="2">Name</th><th rowspan="2">Alias</th><th rowspan="2">Type</th><th rowspan="2">Media</th><th rowspan="2">Connector</th><th rowspan="2">Max Speed</th><th rowspan="2">Standard</th><th rowspan="2">MAC</th><th rowspan="2">Hostname</th><th rowspan="2">IP</th><th colspan="3">Patched To</th></tr>'."\n";
    echo '<tr><th>Device</th><th>Interface</th><th>Add</th></tr>'."\n";
    while($row = mysql_fetch_assoc($result))
    {
	echo '<tr>';
	if($row['di_name'] != null && $row['di_name'] != ""){ echo '<td>'.$row['di_name'].'</td>';} else { echo '<td>&nbsp;</td>';}
	if($row['di_alias'] != null && $row['di_alias'] != ""){ echo '<td>'.$row['di_alias'].'</td>';} else { echo '<td>&nbsp;</td>';}
	if($row['oit_type'] != null && $row['oit_type'] != ""){ echo '<td>'.$row['oit_type'].'</td>';} else { echo '<td>&nbsp;</td>';}
	if($row['oit_media'] != null && $row['oit_media'] != ""){ echo '<td>'.$row['oit_media'].'</td>';} else { echo '<td>&nbsp;</td>';}
	if($row['oit_connector'] != null && $row['oit_connector'] != ""){ echo '<td>'.$row['oit_connector'].'</td>';} else { echo '<td>&nbsp;</td>';}
	if($row['oit_max_speed_bps'] != null){ echo '<td>'.prettySpeed($row['oit_max_speed_bps']).'</td>';} else { echo '<td>&nbsp;</td>';}
	if($row['oit_standard'] != null && $row['oit_standard'] != ""){ echo '<td>'.$row['oit_standard'].'</td>';} else { echo '<td>&nbsp;</td>';}
	if($row['di_mac_address'] != null && $row['di_mac_address'] != ""){ echo '<td>'.formatMAC($row['di_mac_address']).'</td>';} else { echo '<td>&nbsp;</td>';}
	if($row['di_hostname'] != null && $row['di_hostname'] != ""){ echo '<td>'.$row['di_hostname'].'</td>';} else { echo '<td>&nbsp;</td>';}
	if($row['di_ip'] != null && $row['di_ip'] != ""){ echo '<td>'.$row['di_ip'].'</td>';} else { echo '<td>&nbsp;</td>';}
	
	// if we have a record in interface_patches
	if($row['di2_pkey'] != null)
	{
	    echo '<td><a href="viewDevice.php?id='.$row['device2_id'].'">'.$row['device2_name'].'</a></td>';
	    $ifName = "";
	    if($row['di2_name'] != null){ $ifName .= $row['di2_name'];}
	    if($row['di2_alias'] != null){ $ifName .= " (".$row['di2_alias'].")";}
	    if($ifName == ""){ $ifName = $row['di2_pkey'];}
	    echo '<td>'.$ifName.'</td>';
	    echo '<td>&nbsp;</td>';
	}
	else
	{
	    echo '<td>&nbsp;</td><td>&nbsp;</td><td><a href="javascript:addPatch('.$row['di1_pkey'].')">add</a></td>';
	}
	echo '</tr>'."\n";
    }
    echo '</table>'."\n";
}

?>