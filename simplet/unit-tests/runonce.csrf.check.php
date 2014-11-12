<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once __DIR__.'/../functions/generator.string.php';
include_once __DIR__.'/../functions/runonce.create.php';
include_once __DIR__.'/../functions/runonce.csrf.create.php';
include_once __DIR__.'/../functions/runonce.check.php';
include_once __DIR__.'/../functions/runonce.csrf.check.php';
include_once __DIR__.'/../functions/runonce.delete.php';
$Return['Name'] = 'RunOnce CSRF Check';
$Return['Status'] = 'Failure';

$Runonce_CSRF_Create = Runonce_CSRF_Create();
$Runonce_CSRF_Check = Runonce_CSFR_Check($Runonce_CSRF_Create['Key']);

if ( empty($Runonce_CSRF_Check['error']) ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Runonce_CSRF_Check;
} else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], $Runonce_CSRF_Check['error']);
}

$Runonce_Delete = 'DELETE FROM `'.$Database['Prefix'].'Runonce` WHERE `Key`=\''.$Runonce_CSRF_Create['Key'].'\'';
$Runonce_Delete = mysqli_query($Database['Connection'], $Runonce_Delete, MYSQLI_STORE_RESULT);
if (!$Runonce_Delete) 'Error: Invalid Query (Key_Delete): '.mysqli_error($Database['Connection']);

echo API_Output($Return);