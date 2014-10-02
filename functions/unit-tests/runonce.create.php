<?php

include_once __DIR__.'/initialize.php';
include_once __DIR__.'/../generator.string.php';
include_once __DIR__.'/../runonce.create.php';
include_once __DIR__.'/../runonce.delete.php';
$Return['Name'] = 'RunOnce Create';
$Return['Status'] = 'Failure';

$Member_ID = 'RUNONCE_CREATE';

$Runonce_Create = Runonce_Create('1hour', 1, 'RUNONCE_CREATE');

if ( empty($Runonce_Create['error']) ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Runonce_Create;
} else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], $Runonce_Create['error']);
}


$Runonce_Delete = 'DELETE FROM `'.$Database['Prefix'].'Runonce` WHERE `Key`=\''.$Runonce_Create['Key'].'\'';
$Runonce_Delete = mysqli_query($Database['Connection'], $Runonce_Delete, MYSQLI_STORE_RESULT);
if (!$Runonce_Delete) 'Error: Invalid Query (Key_Delete): '.mysqli_error($Database['Connection']);

API_Output($Return);