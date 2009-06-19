<?php
//
// +----------------------------------------------------------------------+
// | RackMan      http://rackman.jasonantman.com                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2009 Jason Antman.                                     |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 3 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to:                           |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | ADDITIONAL TERMS (pursuant to GPL Section 7):                        |
// | 1) You may not remove any of the "Author" or "Copyright" attributions|
// |     from this file or any others distributed with this software.     |
// | 2) If modified, you must make substantial effort to differentiate    |
// |     your modified version from the original, while retaining all     |
// |     attribution to the original project and authors.                 |
// +----------------------------------------------------------------------+
// |Please use the above URL for bug reports and feature/support requests.|
// +----------------------------------------------------------------------+
// | Authors: Jason Antman <jason@jasonantman.com>                        |
// +----------------------------------------------------------------------+
// | $LastChangedRevision::                                             $ |
// | $HeadURL::                                                         $ |
// +----------------------------------------------------------------------+
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
