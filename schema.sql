-- MySQL dump 10.10
--
-- Host: localhost    Database: rack_mgmt
-- ------------------------------------------------------
-- Server version	5.0.26
--
-- $LastChangedRevision$
-- $HeadURL$

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `device_interfaces`
--

DROP TABLE IF EXISTS `device_interfaces`;
CREATE TABLE `device_interfaces` (
  `di_pkey` int(10) unsigned NOT NULL auto_increment,
  `di_device_id` int(10) unsigned default NULL,
  `di_name` varchar(50) default NULL,
  `di_type` int(10) unsigned default NULL,
  `di_is_virtual` tinyint(4) default '0',
  `di_vif_of_id` int(10) unsigned default NULL,
  `di_hostname` varchar(100) default NULL,
  `di_mac_address` varchar(12) default NULL,
  `di_ip_address` varchar(15) default NULL,
  `di_alias` varchar(50) default NULL,
  `di_IF_MIB_ifindex` int(10) unsigned default NULL,
  `di_default_speed_bps` int(10) unsigned default NULL,
  PRIMARY KEY  (`di_pkey`)
) ENGINE=MyISAM AUTO_INCREMENT=246 DEFAULT CHARSET=latin1;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE `devices` (
  `device_id` int(10) unsigned NOT NULL auto_increment,
  `device_class_id` int(10) unsigned default NULL,
  `device_type_id` int(10) unsigned default NULL,
  `device_name` varchar(50) default NULL,
  `device_mfr` varchar(50) default NULL,
  `device_model` varchar(50) default NULL,
  `device_height_U` tinyint(3) unsigned default NULL,
  `device_os_type_id` int(10) unsigned default NULL,
  `device_os_version` varchar(50) default NULL,
  `device_status_id` int(10) unsigned default NULL,
  `device_depth_half_rack` tinyint(1) default '0',
  PRIMARY KEY  (`device_id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

--
-- Table structure for table `devices_rack`
--

DROP TABLE IF EXISTS `devices_rack`;
CREATE TABLE `devices_rack` (
  `dr_pkey` int(10) unsigned NOT NULL auto_increment,
  `dr_rack_id` int(10) unsigned default NULL,
  `dr_device_id` int(11) default NULL,
  `dr_top_U_num` tinyint(3) unsigned default NULL,
  `dr_pending_status` tinyint(1) default '0',
  `dr_rack_side` tinyint(1) default '0',
  PRIMARY KEY  (`dr_pkey`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=latin1;

--
-- Table structure for table `interface_patches`
--

DROP TABLE IF EXISTS `interface_patches`;
CREATE TABLE `interface_patches` (
  `ip_pkey` int(10) unsigned NOT NULL auto_increment,
  `ip_if1_pkey` int(10) unsigned default NULL,
  `ip_if2_pkey` int(10) unsigned default NULL,
  `ip_color` int(10) unsigned default NULL,
  `ip_type` int(10) unsigned default NULL,
  `ip_length_ft` decimal(5,2) default NULL,
  PRIMARY KEY  (`ip_pkey`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `loc_id` int(10) unsigned NOT NULL auto_increment,
  `loc_name` varchar(50) default NULL,
  `loc_desc` varchar(50) default NULL,
  PRIMARY KEY  (`loc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `opt_device_classes`
--

DROP TABLE IF EXISTS `opt_device_classes`;
CREATE TABLE `opt_device_classes` (
  `odc_id` int(10) unsigned NOT NULL auto_increment,
  `odc_name` varchar(50) default NULL,
  PRIMARY KEY  (`odc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Table structure for table `opt_device_os_types`
--

DROP TABLE IF EXISTS `opt_device_os_types`;
CREATE TABLE `opt_device_os_types` (
  `odot_id` int(10) unsigned NOT NULL auto_increment,
  `odot_class_id` int(10) unsigned default NULL,
  `odot_name` varchar(100) default NULL,
  PRIMARY KEY  (`odot_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Table structure for table `opt_device_statuses`
--

DROP TABLE IF EXISTS `opt_device_statuses`;
CREATE TABLE `opt_device_statuses` (
  `ods_id` int(10) unsigned NOT NULL auto_increment,
  `ods_name` varchar(50) default NULL,
  `ods_color` varchar(8) default NULL,
  PRIMARY KEY  (`ods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Table structure for table `opt_device_types`
--

DROP TABLE IF EXISTS `opt_device_types`;
CREATE TABLE `opt_device_types` (
  `odt_id` int(10) unsigned NOT NULL auto_increment,
  `odt_class_id` int(10) unsigned default NULL,
  `odt_name` varchar(50) default NULL,
  PRIMARY KEY  (`odt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Table structure for table `opt_interface_types`
--

DROP TABLE IF EXISTS `opt_interface_types`;
CREATE TABLE `opt_interface_types` (
  `oit_id` int(10) unsigned NOT NULL auto_increment,
  `oit_type` varchar(20) default NULL,
  `oit_media` varchar(20) default NULL,
  `oit_max_speed_bps` int(10) unsigned default NULL,
  `oit_connector` varchar(20) default NULL,
  `oit_standard` varchar(20) default NULL,
  PRIMARY KEY  (`oit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `opt_ip_colors`
--

DROP TABLE IF EXISTS `opt_ip_colors`;
CREATE TABLE `opt_ip_colors` (
  `oipc` int(10) unsigned NOT NULL auto_increment,
  `oipc_name` varchar(20) default NULL,
  PRIMARY KEY  (`oipc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `opt_ip_types`
--

DROP TABLE IF EXISTS `opt_ip_types`;
CREATE TABLE `opt_ip_types` (
  `oipt_id` int(10) unsigned NOT NULL auto_increment,
  `oipt_name` varchar(50) default NULL,
  PRIMARY KEY  (`oipt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `opt_rack_types`
--

DROP TABLE IF EXISTS `opt_rack_types`;
CREATE TABLE `opt_rack_types` (
  `ort_id` int(10) unsigned NOT NULL auto_increment,
  `ort_name` varchar(50) default NULL,
  PRIMARY KEY  (`ort_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Table structure for table `racks`
--

DROP TABLE IF EXISTS `racks`;
CREATE TABLE `racks` (
  `rack_id` int(10) unsigned NOT NULL auto_increment,
  `rack_room_id` int(10) unsigned default NULL,
  `rack_identifier` varchar(20) default NULL,
  `rack_height_U` tinyint(3) unsigned default NULL,
  `rack_type_id` int(10) unsigned default NULL,
  `rack_description` varchar(255) default NULL,
  `rack_two_sided` tinyint(1) default NULL,
  PRIMARY KEY  (`rack_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `room_id` int(10) unsigned NOT NULL auto_increment,
  `room_name` varchar(50) default NULL,
  `room_desc` varchar(255) default NULL,
  `room_location_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`room_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `vlans`
--

DROP TABLE IF EXISTS `vlans`;
CREATE TABLE `vlans` (
  `vlan_id` int(10) unsigned NOT NULL auto_increment,
  `vlan_num` tinyint(3) unsigned default NULL,
  `vlan_name` varchar(50) default NULL,
  `vlan_desc` varchar(255) default NULL,
  PRIMARY KEY  (`vlan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-06-18 23:07:51
