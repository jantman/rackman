<?php
// A PHP5 class to handle some SNMP functions on switches - specifically figuring out what type of switch we have
//
// by Jason Antman <jason@jasonantman.com>
//
// Time-stamp: "2009-02-22 02:00:01 jantman"
// $Id$
// $Source$

// REQUIRES: snmp, PEAR Net_Ping
require_once("Net/Ping.php");

// this is a generic class to do some SNMP things with switches.
// this class should extend a manufacturer- or model-specific class
// the classes that it extends should be in a file with a name containing "SwitchSNMP" and have the class and file named the same
// each class should implement a identifySwitch() method
//   returning boolean true if the switch at the specified IP is the right type for the class
class com_jasonantman_SwitchSNMP
{
    private $switch; // reference to our platform-specific switch object
    public $type; // manufacturer and OS type

    /*
     * constructor takes SNMP info as args, figures out what device/model class to use, and instantiates it
     * @arg IP IP address of switch
     * @arg rocommunity read-only community string
     */
    public function __construct($IP, $rocommunity)
	{
	    if($IP == ""){ throw new InvalidArgumentException("IP address not specified.");}
	    $this->rocommunity = $rocommunity;
	    $this->IP = $IP;
	    // ping the switch
	    $ping = Net_Ping::factory();
	    if(PEAR::isError($ping))
	    {
		echo $ping->getMessage();
	    }
	    else
	    {
		$ping->setArgs(array('count' => 2));
		$result = $ping->ping($this->IP);
		if(PEAR::isError($result) || $result->_received == 0)
		{
		    throw new Exception("No reply on ping.");
		}
	    }

	    // try to get SNMP on the switch
	    if(! snmpget($this->IP, $this->rocommunity, ".1.3.6.1.2.1.1.1.0"))
	    {
		throw new Exception("Cannot execute SNMPget on specified IP.");
	    }

	    // try each of the classes we have
	    $mySwitch = null;
	    
	    // DEBUG - should use getSwitchClasses() and a loop
	    require_once('com_jasonantman_SwitchSNMP_CatOS.php');
	    $temp = new com_jasonantman_SwitchSNMP_CatOS($this->IP, $this->rocommunity);
	    if($temp->identifySwitch()){ $mySwitch = $temp;}
	    // loop through the others
	    // END DEBUG

	    // get the right class for the switch
	    if($mySwitch == null)
	    {
		throw new Exception("No class found to handle device.");
	    }
	    $this->switch = $temp;
	    $this->GET_PORT_RETURN_FIELDS = $mySwitch->GET_PORT_RETURN_FIELDS;
	    $this->type = $mySwitch->type;
	}

    /*
     * This function finds all of the switch type classes in the current directory.
     * @return array array like fileName => className
     */
    private function getSwitchClasses()
	{

	}

    /*
     * PHP5 function to autoload a class that hasn't been loaded yet
     */
    function __autoload($class_name)
	{
	    require_once $class_name . '.php';
	}

    /*
     * Just calls our mySwitch's getPorts() and returns the return value thereof.
     */
    public function getPorts()
	{
	    return $this->switch->getPorts();
	}

    /*
     * Just calls out mySwitch's getPortMACs() method and returns the value thereof.
     */
    public function getPortMACs()
	{
	    return $this->switch->getPortMACs();
	}

}

?>