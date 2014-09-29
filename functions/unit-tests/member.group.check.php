<?php

require 'initialize.php';
require '../member.group.check.php';
$Return['Status'] = 'Failure';

// TODO Member_Create should be a function.
$Member_Create = 'INSERT INTO `'.$Database['Prefix'].'Members` (`ID`, `Mail`, `Groups`) VALUES (\'Member_Group_Check\', \'Member_Group_Check\', \'|Member_Group_Check|\')';
$Member_Create = mysqli_query($Database['Connection'], $Member_Create, MYSQLI_STORE_RESULT);
if ( !$Member_Create ) array_push($Return['Errors'], 'Invalid Query (Member_Create): '.mysqli_error($Database['Connection']));

$var = Member_Group_Check('Member_Group_Check', 'Member_Group_Check');

if (Member_Group_Check('Member_Group_Check', 'Member_Group_Check')) $Return['Status'] = 'Success';
else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], 'Check returned false.');
}

// TODO Member_Delete should be a function.
$Member_Delete = 'DELETE FROM `'.$Database['Prefix'].'Members` WHERE `ID`=\'Member_Group_Check\'';
$Member_Delete = mysqli_query($Database['Connection'], $Member_Delete, MYSQLI_STORE_RESULT);
if ( !$Member_Delete ) array_push($Return['Errors'], 'Invalid Query (Member_Delete): '.mysqli_error($Database['Connection']));

API_Output($Return);