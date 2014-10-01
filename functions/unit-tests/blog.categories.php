<?php

include_once __DIR__.'/initialize.php';
include_once __DIR__.'/../globrecursive.php';
include_once __DIR__.'/../blog.categories.php';
$Return['Name'] = 'Blog Categories';
$Return['Status'] = 'Pending';

// $Return = Blog_Categories('blog.categories.php');
array_push($Return['Errors'], 'Calling Blog Categories doesn\'t work on external locations yet. As such, it is not suitable for a Unit Test.');

API_Output($Return);