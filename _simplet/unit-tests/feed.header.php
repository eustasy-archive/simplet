<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'feed.header.php';
$Return['Name'] = 'Feed Header';
$Return['Status'] = 'Failure';

$URL = 'forum?topic=hello-world';

$Result = Feed_Header($URL);

$Proper = '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<atom:link href="'.$Sitewide['Root'].$URL.'" rel="self" type="application/rss+xml" />
		<title>'.$Sitewide['Title'].'</title>
		<description>'. $Sitewide['Tagline'].'</description>
		<link>'. $Sitewide['Root'].'</link>
		<language>en</language>
		<generator>Simplet</generator>';

if ($Result == $Proper) {
	$Return['Status'] = 'Success';
} else {
	array_push($Return['Errors'], 'Forum_Header did not return the expected text.');
}

$Return['Result'] = $Result;
$Return['Proper'] = $Proper;

echo API_Output($Return);