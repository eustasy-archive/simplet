<?php

include_once __DIR__.'/initialize.php';
include_once __DIR__.'/../member.group.add.php';
include_once __DIR__.'/../member.group.check.php';
$Return['Name'] = 'Member Group Add';
$Return['Status'] = 'Failure';

// TODO Member_Create should be a function.
$Member_Create = 'INSERT INTO `'.$Database['Prefix'].'Members` (`ID`, `Mail`, `Groups`) VALUES (\'Member_Group_Add\', \'Member_Group_Add\', \'\')';
$Member_Create = mysqli_query($Database['Connection'], $Member_Create, MYSQLI_STORE_RESULT);
if ( !$Member_Create ) array_push($Return['Errors'], 'Invalid Query (Member_Create): '.mysqli_error($Database['Connection']));

Member_Group_Add('ADDED_GROUP', 'Member_Group_Add');

if (Member_Group_Check('ADDED_GROUP', 'Member_Group_Add')) $Return['Status'] = 'Success';
else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], 'Check returned false.');
}

// TODO Member_Delete should be a function.
$Member_Delete = 'DELETE FROM `'.$Database['Prefix'].'Members` WHERE `ID`=\'Member_Group_Add\'';
$Member_Delete = mysqli_query($Database['Connection'], $Member_Delete, MYSQLI_STORE_RESULT);
if ( !$Member_Delete ) array_push($Return['Errors'], 'Invalid Query (Member_Delete): '.mysqli_error($Database['Connection']));

API_Output($Return);