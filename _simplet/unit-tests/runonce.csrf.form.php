<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'generator.string.php';
include_once $Backend['functions'].'runonce.create.php';
include_once $Backend['functions'].'runonce.csrf.create.php';
include_once $Backend['functions'].'runonce.csrf.form.php';
include_once $Backend['functions'].'runonce.delete.php';
$Return['Name'] = 'RunOnce CSRF Form';
$Return['Status'] = 'Failure';

$Runonce_CSRF_Form = Runonce_CSRF_Form();

if (
	empty($Runonce_CSRF_Form['error']) &&
	$Runonce_CSRF_Form == '<input type="hidden" name="csrf_protection" value="'.$User['CSRF']['Key'].'">'
) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Runonce_CSRF_Form;
} else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], $Runonce_CSRF_Form['error']);
}

echo API_Output($Return);