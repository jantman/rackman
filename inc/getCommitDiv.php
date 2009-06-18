<?php
// inc/getCommitDiv.php
//
// Time-stamp: "2009-03-05 23:49:02 jantman"
//
// Rack Management Project
// by Jason Antman <jason@jasonantman.com>
// $Id$
// $Source$
require_once('../config/config.php');
require_once('funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

// BEGIN commit/rollback form
$query = "SELECT dr_device_id FROM devices_rack WHERE dr_pending_status!=0;";
$result = mysql_query($query) or die("Error in query: ".$query."\nError: ".mysql_error());
if(mysql_num_rows($result) > 0)
{
    echo '<form name="commitForm" method="POST" action="handlers/commitRack.php">'."\n";
    echo '<input type="hidden" name="rackID" value="'.$_GET['rack_id'].'" />'."\n";
    echo '<input type="Submit" name="submit" value="Commit">'."\n";
    echo '<input type="Submit" name="submit" value="Rollback">'."\n";
    echo '</form>'."\n";
}
// END commit/rollback form

?>