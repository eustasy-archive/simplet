<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'forum.categories.php';
$Return['Name'] = 'Forum Categories';
$Return['Status'] = 'Pending';

// $Return = Feed_Forum();
array_push($Return['Errors'], 'Calling Forum Categories doesn\'t work on external locations yet. As such, it is not suitable for a Unit Test.');

echo API_Output($Return);