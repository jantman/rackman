<?php
// help/objects.php
//
// Time-stamp: "2009-02-19 22:00:26 jantman"
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
<title>Objects - RackMan Help</title>
<link rel="stylesheet" type="text/css" href="help.css" />
</head>
<body>
<h1>RackMan Objects</h1>

<p>While RackMan is mostly programmed in functional PHP, the real-world things and connections which it describes are best called 'objects'. It must be noted that data storage is in a MySQL database, with the schema designed to mirror these real-world objects and relationships.</p>

<h2>General Objects (from least to most specific)</h2>
<ul>
<li><strong>Locations</strong> are the most general of the three locational objects, intended to describe buildings or sites.</li>
<li><strong>Rooms</strong> are the middle of the three locational objects, intended to describe a room within a building/site.</li>
<li><strong>Racks</strong> are the most specific of the locational objects, and also are the locational object directly associated with devices. Each device should be associated with a specific rack, and this is used as the basis for the graphical view of the device location.</li>
<li>The <strong><span style="font-style: italic;">Holding Area</span></strong> is a pseudo-location composed of all devices which are not assigned to racks. All newly created devices appear here.</li>
<li><strong>Devices</strong> describe physical objects placed in racks or otherwise. Everything in a rack must be a "Device". They need not have any child objects - things such as cable management, rack blanks, and patch panels are also devices.</li>
<li><strong>Interfaces</strong> are child objects of devices. Each device may have any number of interfaces, which represent any connection point to the device. They need not be network interfaces - serial, power, KVM, anything that is conneted to (or can be connected to) a device is fine.</li>
</ul>

<?php
require_once('../footer.php');
?>
</body>

</html>
