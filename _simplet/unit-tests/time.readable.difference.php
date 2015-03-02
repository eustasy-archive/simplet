<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'time.readable.difference.php';
$Return['Name'] = 'Time Readable Difference';
$Return['Status'] = 'Failure';

$Return['Result'] = array();
$Return['Result']['Seconds'] = Time_Readable_Difference($Time['Now']-1);
$Return['Result']['Minutes'] = Time_Readable_Difference($Time['Now']-120);
$Return['Result']['Hours'] = Time_Readable_Difference($Time['Now']-10800);
$Return['Result']['Days'] = Time_Readable_Difference($Time['Now']-345600);
$Return['Result']['Weeks'] = Time_Readable_Difference($Time['Now']-604800);
$Return['Result']['Months'] = Time_Readable_Difference($Time['Now']-5356800);
$Return['Result']['Years'] = Time_Readable_Difference($Time['Now']-94608000);
$Return['Result']['Decades'] = Time_Readable_Difference($Time['Now']-1261440000);
$Return['Result']['inSeconds'] = Time_Readable_Difference($Time['Now']+1);
$Return['Result']['inMinutes'] = Time_Readable_Difference($Time['Now']+120);
$Return['Result']['inHours'] = Time_Readable_Difference($Time['Now']+10800);
$Return['Result']['inDays'] = Time_Readable_Difference($Time['Now']+345600);
$Return['Result']['inWeeks'] = Time_Readable_Difference($Time['Now']+604800);
$Return['Result']['inMonths'] = Time_Readable_Difference($Time['Now']+5356800);
$Return['Result']['inYears'] = Time_Readable_Difference($Time['Now']+94608000);
$Return['Result']['inDecades'] = Time_Readable_Difference($Time['Now']+1261440000);

if (
	$Return['Result']['Seconds']['Prefered'] == '1 Second ago' &&
	$Return['Result']['Minutes']['Prefered'] == '2 Minutes ago' &&
	$Return['Result']['Hours']['Prefered'] == '3 Hours ago' &&
	$Return['Result']['Days']['Prefered'] == '4 Days ago' &&
	$Return['Result']['Weeks']['Prefered'] == '1 Week ago' &&
	$Return['Result']['Months']['Prefered'] == '2 Months ago' &&
	$Return['Result']['Years']['Prefered'] == '3 Years ago' &&
	$Return['Result']['Decades']['Prefered'] == '4 Decades ago' &&
	$Return['Result']['inSeconds']['Prefered'] == 'in 1 Second' &&
	$Return['Result']['inMinutes']['Prefered'] == 'in 2 Minutes' &&
	$Return['Result']['inHours']['Prefered'] == 'in 3 Hours' &&
	$Return['Result']['inDays']['Prefered'] == 'in 4 Days' &&
	$Return['Result']['inWeeks']['Prefered'] == 'in 1 Week' &&
	$Return['Result']['inMonths']['Prefered'] == 'in 2 Months' &&
	$Return['Result']['inYears']['Prefered'] == 'in 3 Years' &&
	$Return['Result']['inDecades']['Prefered'] == 'in 4 Decades'
) {
	$Return['Status'] = 'Success';
} else {
	echo 'no';
	$Return['Status'] = 'Failure';
	// TODO Errors
	// array_push($Return['Errors'], 'Check returned false.');
}

echo API_Output($Return);