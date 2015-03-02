<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'api.output.xml.php';
$Return['Name'] = 'API Output XML';
$Return['Status'] = 'Success';

$XML = API_Output_XML($Return);

if ( $XML ) {
	echo json_encode($XML);
} else {
	$Return['Status'] = 'Failure';
	$Return['Errors'] = array();
	array_push($Return['Errors'], 'Functions returned false.');
	echo API_Output($Return);
}