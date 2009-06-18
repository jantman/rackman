<?php
// help/features.php
//
// Time-stamp: "2009-02-19 21:30:37 jantman"
//
// RackMan
// by Jason Antman <jason@jasonantman.com>
// $Id$
// $Source$
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Features - RackMan Help</title>
<link rel="stylesheet" type="text/css" href="help.css" />
</head>
<body>
<h1>RackMan Features</h1>

<h2>Maain Features:</h2>
<ul>
<li>Define any number of locations, rooms and racks that hold devices.</li>
<li>Define devices which contain hardware configuration data.</li>
<li>Add devices to racks for a graphical view. Easily move devices within a rack and between racks.</li>
<li>Obtain a quick, easy grpahical view of devices within a rack.</li>
<li>Define interfaces for devices (user-defined types, not limited to network interfaces).</li>
<li>Track patches between interfaces.</li>
</ul>

<h2>Optional Automation:</h2>
<ul>
<li>Integration with inventory tracking system (collector scripts on hosts output XML, parsed server-side into database).</li>
<li>Automated discovery of device patching via SNMP on switches.</li>
</ul>

<?php
require_once('../footer.php');
?>
</body>

</html>
