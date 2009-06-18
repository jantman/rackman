<?php
//
// handlers/patchInterface.php
//
// handler for patching two interfaces together
// Time-stamp: "2009-02-28 01:28:55 jantman"
// $Id$
// $Source$

if(isset($_POST['id']))
{
    $id = (int)$_POST['id'];
}
else
{
    die("ERROR: No ID specified.\n");
}

if(isset($_POST['patchToID']))
{
    $patchTo = (int)$_POST['patchToID'];
}
else
{
    die("ERROR: No Patch to ID specified.\n");
}

require_once('../config/config.php');
require_once('../inc/funcs.php.inc');
mysql_connect() or die("Error connecting to MySQL.\n");
mysql_select_db($dbName) or die("Error selecting MySQL database: ".$dbName."\n");

$query = "INSERT INTO interface_patches SET ip_if1_pkey=".$id.",ip_if2_pkey=".$patchTo.";";
$result = mysql_query($query) or die("ERROR: Error in query: ".$query."\nError: ".mysql_error());
$query = "INSERT INTO interface_patches SET ip_if1_pkey=".$patchTo.",ip_if2_pkey=".$id.";";
$result = mysql_query($query) or die("ERROR: Error in query: ".$query."\nError: ".mysql_error());

?>