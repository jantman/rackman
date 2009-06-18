#!/usr/bin/php
<?php

$query = "SELECT ip.ip_if1_pkey,ip.ip_if2_pkey,di1.di_name AS di1_name,di1.di_alias AS di1_alias,d1.device_name AS d1_name,di2.di_name AS di2_name,di2.di_alias AS di2_alias,d2.device_name AS d2_name FROM interface_patches AS ip LEFT JOIN device_interfaces AS di1 ON ip.ip_if1_pkey=di1.di_pkey LEFT JOIN devices AS d1 ON di1.di_device_id=d1.device_id LEFT JOIN device_interfaces AS di2 ON ip.ip_if2_pkey=di2.di_pkey LEFT JOIN devices AS d2 ON di2.di_device_id=d2.device_id;";

?>