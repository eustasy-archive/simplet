<?php

if(
	isset($Database_Host) &&
	isset($Database_User) &&
	isset($Database_Pass) &&
	isset($Database_Name) &&
	!empty($Database_Host) &&
	!empty($Database_User) &&
	!empty($Database_Pass) &&
	!empty($Database_Name)
	) {
	$MySQL_Connection = mysqli_connect($Database_Host, $Database_User, $Database_Pass, $Database_Name);
	if (!$MySQL_Connection) $MySQL_Connection_Error = mysqli_connect_error($MySQL_Connection);
	else $MySQL_Connection_Error = false;
} else {
	$MySQL_Connection = false;
	$MySQL_Connection_Error = 'Error(s): ';
	if(!isset($Database_Host) || empty($Database_Host)) $MySQL_Connection_Error .= 'No Database Host Configured. ';
	if(!isset($Database_User) || empty($Database_User)) $MySQL_Connection_Error .= 'No Database User Configured. ';
	if(!isset($Database_Pass) || empty($Database_Pass)) $MySQL_Connection_Error .= 'No Database Pass Configured. ';
	if(!isset($Database_Name) || empty($Database_Name)) $MySQL_Connection_Error .= 'No Database Name Configured. ';
}