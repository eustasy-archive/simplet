<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'generator.string.php';
include_once $Backend['functions'].'runonce.create.php';
include_once $Backend['functions'].'runonce.csrf.create.php';
include_once $Backend['functions'].'runonce.check.php';
include_once $Backend['functions'].'runonce.csrf.check.php';
include_once $Backend['functions'].'runonce.delete.php';
$Return['Name'] = 'RunOnce CSRF Check';
$Return['Status'] = 'Failure';

$Runonce_CSRF_Check = Runonce_CSRF_Check($User['CSRF']['Cookie']);

if ( empty($Runonce_CSRF_Check['error']) ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Runonce_CSRF_Check;
} else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], $Runonce_CSRF_Check['error']);
}

echo API_Output($Return);