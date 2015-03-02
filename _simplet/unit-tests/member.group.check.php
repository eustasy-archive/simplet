<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'member.group.check.php';
$Return['Name'] = 'Member Group Check';
$Return['Status'] = 'Failure';

// TODO Member_Create should be a function.
$Member_Create = 'INSERT INTO `'.$Database['Prefix'].'Members` (`ID`, `Mail`, `Groups`) VALUES (\'Member_Group_Check\', \'Member_Group_Check\', \'|Member_Group_Check|\')';
$Member_Create = mysqli_query($Database['Connection'], $Member_Create, MYSQLI_STORE_RESULT);
if ( !$Member_Create ) array_push($Return['Errors'], 'Invalid Query (Member_Create): '.mysqli_error($Database['Connection']));

$Member_Group_Check = Member_Group_Check('Member_Group_Check', 'Member_Group_Check');

if ( $Member_Group_Check ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Member_Group_Check;
} else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], 'Check returned false.');
}

// TODO Member_Delete should be a function.
$Member_Delete = 'DELETE FROM `'.$Database['Prefix'].'Members` WHERE `ID`=\'Member_Group_Check\'';
$Member_Delete = mysqli_query($Database['Connection'], $Member_Delete, MYSQLI_STORE_RESULT);
if ( !$Member_Delete ) array_push($Return['Errors'], 'Invalid Query (Member_Delete): '.mysqli_error($Database['Connection']));

echo API_Output($Return);