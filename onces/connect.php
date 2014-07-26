<?php

if(
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
	if(empty($Database_Host)) $MySQL_Connection_Error .= 'No Database Host Configured. ';
	if(empty($Database_User)) $MySQL_Connection_Error .= 'No Database User Configured. ';
	if(empty($Database_Pass)) $MySQL_Connection_Error .= 'No Database Pass Configured. ';
	if(empty($Database_Name)) $MySQL_Connection_Error .= 'No Database Name Configured. ';
}