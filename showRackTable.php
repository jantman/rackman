<?php
// index.php
//
// main index page
// Time-stamp: "2009-06-18 19:26:18 jantman"
//
// Rack Management Project
// by Jason Antman <jason@jasonantman.com>
// $Id$
// $Source$
require_once('config/config.php');
require_once('inc/funcs.php.inc');
require_once('inc/rackTable.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

if(isset($_GET['rack_id']))
{
    $id = (int)$_GET['rack_id'];
    showRackTable($id);
}

?>

