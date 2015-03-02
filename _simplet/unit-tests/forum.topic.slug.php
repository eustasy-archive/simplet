<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'forum.topic.check.php';
include_once $Backend['functions'].'forum.topic.slug.php';
$Return['Name'] = 'Forum Topic Slug';
$Return['Status'] = 'Failure';

$Empty = Forum_Topic_Slug('<\';');
// TODO More tests for various things.

if (
	$Empty == 'topic-1'
) {
	$Return['Status'] = 'Success';
	$Return['Result']['Empty'] = $Empty;
} else {
	if ( $Empty == 'topic-1' ) array_push($Return['Errors'], '$Empty doesn\'t return the expected value.');
}

echo API_Output($Return);