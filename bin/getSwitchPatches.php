#!/usr/bin/php
<?php
// Script to populate the device_interfaces table for a switch from SNMP
// Time-stamp: "2009-02-22 02:16:51 jantman"
// $Id$
// $Source$
require_once('../inc/com_jasonantman_SwitchSNMP.php');
require_once('../inc/funcs.php.inc');
require_once('../config/config.php');

// connect to MySQL
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

// TODO - loop through our switches, call doSwitchPorts for each
$topquery = "SELECT d.device_id,d.device_name FROM devices AS d LEFT JOIN opt_device_classes AS odc ON odc.odc_id=d.device_class_id LEFT JOIN opt_device_types AS odt ON odt.odt_id=d.device_type_id WHERE odc.odc_name='Network' AND odt.odt_name LIKE '%switch%';";
$topresult = mysql_query($topquery) or die("Error in query: ".$topquery."\nError: ".mysql_error());
while($toprow = mysql_fetch_assoc($topresult))
{
    doSwitchPatches($toprow['device_id'], $toprow['device_name'], 'public');
}

function doSwitchPatches($device_id, $ip, $rocommunity)
{
    //fwrite(STDERR, "Working on switch ".$ip." ifindex ".$ifindex."\n");
    try
    {
	$switch = new com_jasonantman_SwitchSNMP($ip, $rocommunity);
    }
    catch (Exception $e)
	{
	    fwrite(STDERR, "ERROR: Attempt to construct com_jasonantman_SwitchSNMP threw exception: ".$e->getMessage()."\n");
	    return false;
	}

    // ok, we have a switch object, get the MACs on our port
    $MACs = $switch->getPortMACs();

    foreach($MACs as $idx => $arr)
    {
	if(count($arr) > 0)
	{
	    // get the di_pkey for this interface
	    $query = "SELECT di_pkey FROM device_interfaces WHERE di_device_id=".$device_id." AND di_IF_MIB_ifindex=".$idx.";";
	    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
	    $row = mysql_fetch_assoc($result);
	    $if_pkey = $row['di_pkey'];
	    // loop through the indexes and check the DB for each of them, by MAC
	    foreach($arr as $MAC)
	    {
		// see if we have this MAC in our DB
		$query = "SELECT di_pkey FROM device_interfaces WHERE di_mac_address='".$MAC."';";
		$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
		if(mysql_num_rows($result) > 0)
		{
		    // we have the MAC in the DB, see if there's an existing patch
		    $row = mysql_fetch_assoc($result);
		    $other_pkey = $row['di_pkey'];
		    $query = "SELECT ip_pkey FROM interface_patches WHERE ip_if1_pkey=".$if_pkey." AND ip_if2_pkey=".$other_pkey.";";
		    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
		    // if we don't already have one...
		    if(mysql_num_rows($result) == 0)
		    {
			$query = "INSERT INTO interface_patches SET ip_if1_pkey=".$if_pkey.",ip_if2_pkey=".$other_pkey.";";
			$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
			$query = "INSERT INTO interface_patches SET ip_if1_pkey=".$other_pkey.",ip_if2_pkey=".$if_pkey.";";
			$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
			// DONE adding the patch between interfaces
		    }
		}
	    }
	}
    }

}

?>
