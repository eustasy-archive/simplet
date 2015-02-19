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

$Runonce_CSRF_Check = Runonce_CSRF_Check($User_CSRF['Cookie']);

if ( empty($Runonce_CSRF_Check['error']) ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Runonce_CSRF_Check;
} else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], $Runonce_CSRF_Check['error']);
}

echo API_Output($Return);