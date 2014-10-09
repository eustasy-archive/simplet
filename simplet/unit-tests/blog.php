<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once __DIR__.'/../functions/blog.php';
$Return['Name'] = 'Blog';
$Return['Status'] = 'Pending';

// $Return = Blog_Categories(__DIR__);
array_push($Return['Errors'], 'Calling Blog doesn\'t work on external locations yet. As such, it is not suitable for a Unit Test.');

echo API_Output($Return);