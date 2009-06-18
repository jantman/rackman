<?php
// handlers/addToRack.php
//
// handler to commit or rollback changes
// $Id$
// $Source$

require_once('../config/config.php');
require_once('../inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

$rackID = (int)$_POST['rackID'];

if($_POST['submit'] == "Commit")
{
    $query = "UPDATE devices_rack SET dr_pending_status=0 WHERE dr_pending_status=1 AND dr_rack_id=".$rackID.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $query = "DELETE FROM devices_rack WHERE dr_pending_status=2 AND dr_rack_id=".$rackID.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
}
elseif($_POST['submit'] == "Rollback")
{
    $query = "UPDATE devices_rack SET dr_pending_status=0 WHERE dr_pending_status=2 AND dr_rack_id=".$rackID.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
    $query = "DELETE FROM devices_rack WHERE dr_pending_status=1 AND dr_rack_id=".$rackID.";";
    $result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
}

if($result)
{
    header("Location: ../viewRack.php?rack_id=".$_POST['rackID']);
}


?>