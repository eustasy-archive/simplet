<?php

require 'initialize.php';
require '../member.group.list.php';
$Return['Status'] = 'Failure';

// TODO Member_Create should be a function.
$Member_Create = 'INSERT INTO `'.$Database['Prefix'].'Members` (`ID`, `Mail`, `Groups`) VALUES (\'Member_Group_List_1\', \'Member_Group_List_1\', \'|Member_Group_List|\'), (\'Member_Group_List_2\', \'Member_Group_List_2\', \'|Member_Group_List|\'), (\'Member_Group_List_3\', \'Member_Group_List_3\', \'|Member_Group_List|\')';
$Member_Create = mysqli_query($Database['Connection'], $Member_Create, MYSQLI_STORE_RESULT);
if ( !$Member_Create ) array_push($Return['Errors'], 'Invalid Query (Member_Create): '.mysqli_error($Database['Connection']));

$Member_Group_List = Member_Group_List('Member_Group_List');
if ( !$Member_Group_List ) {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], 'List returned false.');
} else {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Member_Group_List;
}

// TODO Member_Delete should be a function.
$Member_Delete = 'DELETE FROM `'.$Database['Prefix'].'Members` WHERE `ID` LIKE \'%Member_Group_List_%\'';
$Member_Delete = mysqli_query($Database['Connection'], $Member_Delete, MYSQLI_STORE_RESULT);
if ( !$Member_Delete ) array_push($Return['Errors'], 'Invalid Query (Member_Delete): '.mysqli_error($Database['Connection']));

API_Output($Return);