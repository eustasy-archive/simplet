<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once __DIR__.'/../functions/forum.category.count.php';
$Return['Name'] = 'Forum Category Count';
$Return['Status'] = 'Failure';

// TODO Topic_Create should be a function.
$Topic_Create = 'INSERT INTO `'.$Database['Prefix'].'Topics` (`Status`, `Category`, `Slug`, `Title`) VALUES (\'None\', \'Category\', \'forum-category-count-open\', \'Forum Category Count: Open\'), (\'Public\', \'Category\', \'forum-category-count-public\', \'Forum Category Count: Public\'), (\'Private\', \'Category\', \'forum-category-count-private\', \'Forum Category Count: Private\'), (\'Hidden\', \'Category\', \'forum-category-count-hidden\', \'Forum Category Count: Hidden\')';
$Topic_Create = mysqli_query($Database['Connection'], $Topic_Create, MYSQLI_STORE_RESULT);
if ( !$Topic_Create ) array_push($Return['Errors'], 'Invalid Query (Topic_Create): '.mysqli_error($Database['Connection']));

$Count = Forum_Category_Count('Category');

if ( $Count == 2 ) $Return['Status'] = 'Success';

$Return['Result'] = $Count;

// TODO Category_Delete should be a function.
$Topic_Delete = 'DELETE FROM `'.$Database['Prefix'].'Topics` WHERE `Category`=\'Category\'';
$Topic_Delete = mysqli_query($Database['Connection'], $Topic_Delete, MYSQLI_STORE_RESULT);
if ( !$Topic_Delete ) array_push($Return['Errors'], 'Invalid Query (Topic_Delete): '.mysqli_error($Database['Connection']));

echo API_Output($Return);