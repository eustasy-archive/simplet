<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once __DIR__.'/../functions/feed.files.php';
$Return['Name'] = 'Feed Files';
$Return['Status'] = 'Pending';

// $Return = Feed_Files(__DIR__);
array_push($Return['Errors'], 'Calling Feed Files doesn\'t work on external locations yet. As such, it is not suitable for a Unit Test.');

echo API_Output($Return);