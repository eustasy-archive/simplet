<?php

require 'initialize.php';
require '../api.output.xml.php';
$Return['Name'] = 'API Output XML';
$Return['Status'] = 'Success';

$XML = API_Output_XML($Return);

if ( $XML ) echo json_encode($XML);
else {
	$Return['Status'] = 'Failure';
	$Return['Errors'] = array();
	array_push($Return['Errors'], 'Functions returned false.');
	API_Output($Return);
}