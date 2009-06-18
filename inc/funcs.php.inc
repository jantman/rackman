<?php

function getHosts($rackID)
{
    $query = "SELECT dr.dr_device_id,dr.dr_top_U_num,dr.dr_pending_status,d.device_id,d.device_name,d.device_mfr,d.device_model,d.device_height_U,d.device_status_id,s.ods_name FROM devices_rack AS dr LEFT JOIN devices AS d ON d.device_id=dr.dr_device_id LEFT JOIN opt_device_statuses AS s ON d.device_status_id=s.ods_id WHERE dr.dr_rack_id=".$rackID." ORDER BY dr.dr_top_U_num DESC;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    
    $hosts = array(); // array to hold the host info

    // populate arrays with all of the info
    while($row = mysql_fetch_assoc($result))
    {
	$temp = array("name" => $row['device_name'], "mfr" => $row['device_mfr'], "model" => $row['device_model'], "height_U" => $row['device_height_U'], "status" => $row['ods_name'], "status_id" => $row['device_status_id'], "pending" => $row['dr_pending_status']);
	// 	$rackUnits[$row['dr_top_U_num']] = $row['dr_device_id'];
	$hosts[$row['device_id']] = $temp;
    }
    return $hosts;
}

// returns an array of rack U spaces to Host IDs, -1 if empty
function getRUtoHosts($rackID)
{
    $rackUnits = array(); // array to hold what's in each of the rack units

    // get the rack height
    $query = "SELECT ra.rack_id,ra.rack_height_U FROM racks AS ra WHERE ra.rack_id=".$rackID.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $row = mysql_fetch_assoc($result);
    $rack_height = $row['rack_height_U'];

    // populate the array with placeholder entries for each U
    for($i = $rack_height; $i > 0; $i--)
    {
	$rackUnits[$i] = -1; // placeholder
    }

    $query = "SELECT dr.dr_device_id,dr.dr_top_U_num,d.device_height_U FROM devices_rack AS dr LEFT JOIN devices AS d ON d.device_id=dr.dr_device_id WHERE dr.dr_rack_id=".$rackID." AND (dr_pending_status=0 OR dr_pending_status=1) ORDER BY dr.dr_top_U_num DESC;";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    
    // populate arrays with all of the info
    while($row = mysql_fetch_assoc($result))
    {
	for($i = $row['dr_top_U_num']; $i > ($row['dr_top_U_num'] - $row['device_height_U']); $i--)
	{
	    $rackUnits[$i] = $row['dr_device_id'];
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


?>