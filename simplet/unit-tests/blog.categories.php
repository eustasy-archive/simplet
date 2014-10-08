<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once __DIR__.'/../functions/globrecursive.php';
include_once __DIR__.'/../functions/blog.categories.php';
$Return['Name'] = 'Blog Categories';
$Return['Status'] = 'Pending';

// $Return = Blog_Categories('blog.categories.php');
array_push($Return['Errors'], 'Calling Blog Categories doesn\'t work on external locations yet. As such, it is not suitable for a Unit Test.');

echo API_Output($Return);