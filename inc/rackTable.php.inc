<?php

function showRackTable($id)
{
    // show the one rack
    $query = "SELECT ra.rack_id,ra.rack_identifier,ra.rack_height_U,ra.rack_two_sided,ro.room_name,l.loc_name FROM racks AS ra LEFT JOIN rooms AS ro ON ra.rack_room_id=ro.room_id LEFT JOIN locations AS l ON ro.room_location_id=l.loc_id WHERE ra.rack_id=".$id.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $row = mysql_fetch_assoc($result);
    $rack_height = $row['rack_height_U'];
    if($row['rack_two_sided'] == 1){ $isTwoSided = true;} else { $isTwoSided = false;}

    $hosts = getHosts($id);
    $rackUnits = getRUtoHosts($id);

    // show status colors
    $statusColors = array();
    $query = "SELECT * FROM opt_device_statuses;";
    $myresult = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    while($myrow = mysql_fetch_assoc($myresult))
    {
	$statusColors[$myrow['ods_id']] = $myrow['ods_color'];
    }
    // end status colors

    //echo '<pre>';
    //echo var_dump($rackUnits);
    //echo '</pre>';

    // output the rack HTML table
    echo '<table class="rackTable">'."\n";
    if($isTwoSided) { echo '<tr><th>&nbsp;</th><th>Front</th><td class="sideSeparator">&nbsp;</td><th>Rear</th></tr>'."\n";}
    $lastHostF = -1;
    $lastHostR = -1;
    for($i = $rack_height; $i > 0; $i--)
    {
	echo '<tr><th>'.$i.'</th>';
	// do the front of the rack, which is always present
	if($rackUnits[1][$i] != -1 && $rackUnits[1][$i] != $lastHostF)
	{
	    $hostID = $rackUnits[1][$i];
	    if($hosts[$hostID]['height_U'] > 1) { echo '<td rowspan="'.$hosts[$hostID]['height_U'].'" ';} else{ echo '<td ';}
	    if($hosts[$hostID]['pending'] != 0){ echo ' style="background-color: white;">**';} else { echo ' style="background-color: #'.$statusColors[$hosts[$hostID]['status_id']].';">';}
	    echo '<a href="viewDevice.php?id='.$hostID.'">'.$hosts[$hostID]['name'].'</a>'.'&nbsp;&nbsp;&nbsp;('.$hosts[$hostID]['mfr'].' '.$hosts[$hostID]['model'].') (<a href="handlers/editDevice.php?id='.$hostID.'">e</a> <a href="handlers/removeDeviceFromRack.php?device_id='.$hostID.'&rack_id='.$id.'">r</a> <a href="javascript:showMoveForm('.$hostID.')">m</a>)</td>';
	    $lastHostF = $hostID;
	}
	elseif($rackUnits[1][$i] != -1 && $rackUnits[1][$i] == $lastHostF)
	{
	    // do nothing
	}
	else
	{
	    // open space
	    echo '<td style="background-color: #CCCCCC;">&nbsp;</td>';
	}

	if($isTwoSided)
	{
	    // DO REAR SIDE
	    echo '<td class="sideSeparator">&nbsp;</td>';
	    if($rackUnits[1][$i] == $rackUnits[2][$i] && $hostID != $lastHostR && $lastHostR != -1 && $rackUnits[1][$i] != -1)
	    {
		if($hosts[$hostID]['height_U'] > 1) { echo '<td rowspan="'.$hosts[$hostID]['height_U'].'" ';} else{ echo '<td ';}
		if($hosts[$hostID]['pending'] != 0) { echo '" style="background-color: white;">**'; } else { echo '" style="background-color: #'.$statusColors[$hosts[$hostID]['status_id']].';">';}
		echo '&lt; ------- </td>';
		$lastHostR = $hostID;
	    }
	    elseif($rackUnits[2][$i] != -1 && $rackUnits[2][$i] != $lastHostR)
	    {
		$hostID = $rackUnits[2][$i];
		if($hosts[$hostID]['height_U'] > 1) { echo '<td rowspan="'.$hosts[$hostID]['height_U']; } else{ echo '<td ';}
		if($hosts[$hostID]['pending'] != 0) { echo '" style="background-color: white;">**'; } else { echo '" style="background-color: #'.$statusColors[$hosts[$hostID]['status_id']].';">';}
		echo '<a href="viewDevice.php?id='.$hostID.'">'.$hosts[$hostID]['name'].'</a>'.'&nbsp;&nbsp;&nbsp;('.$hosts[$hostID]['mfr'].' '.$hosts[$hostID]['model'].') (<a href="handlers/editDevice.php?id='.$hostID.'">e</a> <a href="handlers/removeDeviceFromRack.php?device_id='.$hostID.'&rack_id='.$id.'">r</a> <a href="javascript:showMoveForm('.$hostID.')">m</a>)</td>';
		$lastHostR = $hostID;
	    }
	    elseif($rackUnits[2][$i] != -1 && $rackUnits[2][$i] == $lastHostR)
	    {
		// do nothing
	    }
	    else
	    {
		// open space
		echo '<td style="background-color: #CCCCCC;">&nbsp;</td>';
	    }

	}
	echo '</tr>'."\n";
    }

    echo '</table>'."\n";
}

?>