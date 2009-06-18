<?php
// showInterfaceTable.php
//
// main index page
// Time-stamp: "2009-02-28 01:30:33 jantman"
//
// Rack Management Project
// by Jason Antman <jason@jasonantman.com>
// $Id$
// $Source$
require_once('config/config.php');
require_once('inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

if(isset($_GET['id']))
{
    $id = (int)$_GET['id'];
    require_once('inc/devices.php.inc');
    getInterfaceTable($id);

}

?>

