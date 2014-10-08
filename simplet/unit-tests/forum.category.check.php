<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once __DIR__.'/../functions/forum.category.check.php';
$Return['Name'] = 'Forum Category Check';
$Return['Status'] = 'Failure';

// TODO Category_Create should be a function.
$Category_Create = 'INSERT INTO `'.$Database['Prefix'].'Categories` (`Status`, `Title`, `Slug`) VALUES (\'Open\', \'Forum Category Check: Open\', \'forum-category-check-open\'), (\'Public\', \'Forum Category Check: Public\', \'forum-category-check-public\'), (\'Private\', \'Forum Category Check: Private\', \'forum-category-check-private\')';
$Category_Create = mysqli_query($Database['Connection'], $Category_Create, MYSQLI_STORE_RESULT);
if ( !$Category_Create ) array_push($Return['Errors'], 'Invalid Query (Category_Create): '.mysqli_error($Database['Connection']));

$Check_Open = Forum_Category_Check('forum-category-check-open');
$Check_Public = Forum_Category_Check('forum-category-check-public');
$Check_Private = Forum_Category_Check('forum-category-check-private');
$Check_None = Forum_Category_Check('forum-category-check-none');

$Member_Auth = false;

$Check_Status_Open = Forum_Category_Check('forum-category-check-open', true);
$Check_Status_Public = Forum_Category_Check('forum-category-check-public', true);
$Check_Status_Private = Forum_Category_Check('forum-category-check-private', true);
$Check_Status_None = Forum_Category_Check('forum-category-check-none', true);

$Member_Auth = true;

$Check_Auth_Open = Forum_Category_Check('forum-category-check-open', true);
$Check_Auth_Public = Forum_Category_Check('forum-category-check-public', true);
$Check_Auth_Private = Forum_Category_Check('forum-category-check-private', true);
$Check_Auth_None = Forum_Category_Check('forum-category-check-none', true);

if (
	$Check_Open &&
	$Check_Public &&
	$Check_Private &&
	!$Check_None &&
	!$Check_Status_Open &&
	$Check_Status_Public &&
	!$Check_Status_Private &&
	!$Check_Status_None &&
	!$Check_Auth_Open &&
	$Check_Auth_Public &&
	$Check_Auth_Private &&
	!$Check_Auth_None
) {
	$Return['Status'] = 'Success';
	$Return['Result']['Check Open'] = $Check_Open;
	$Return['Result']['Check Public'] = $Check_Public;
	$Return['Result']['Check Private'] = $Check_Private;
	$Return['Result']['Check None'] = $Check_None;
	$Return['Result']['Check Status Open'] = $Check_Status_Open;
	$Return['Result']['Check Status Public'] = $Check_Status_Public;
	$Return['Result']['Check Status Private'] = $Check_Status_Private;
	$Return['Result']['Check Status None'] = $Check_Status_None;
	$Return['Result']['Check Auth Open'] = $Check_Auth_Open;
	$Return['Result']['Check Auth Public'] = $Check_Auth_Public;
	$Return['Result']['Check Auth Private'] = $Check_Auth_Private;
	$Return['Result']['Check Auth None'] = $Check_Auth_None;
} else {
	$Return['Status'] = 'Failure';
	if ( !$Check_Open ) array_push($Return['Errors'], 'Check_Open returned false.');
	if ( !$Check_Public ) array_push($Return['Errors'], 'Check_Public returned false.');
	if ( !$Check_Private ) array_push($Return['Errors'], 'Check_Private returned false.');
	if ( $Check_None ) array_push($Return['Errors'], 'Check_None returned true.');
	if ( $Check_Status_Open ) array_push($Return['Errors'], 'Check_Status_Open returned true.');
	if ( !$Check_Status_Public ) array_push($Return['Errors'], 'Check_Status_Public returned false.');
	if ( $Check_Status_Private ) array_push($Return['Errors'], 'Check_Status_Private returned true.');
	if ( $Check_Status_None ) array_push($Return['Errors'], 'Check_Status_None returned true.');
	if ( $Check_Auth_Open ) array_push($Return['Errors'], 'Check_Auth_Open returned true.');
	if ( !$Check_Auth_Public ) array_push($Return['Errors'], 'Check_Auth_Public returned false.');
	if ( !$Check_Auth_Private ) array_push($Return['Errors'], 'Check_Auth_Private returned false.');
	if ( $Check_Auth_None ) array_push($Return['Errors'], 'Check_Auth_None returned true.');
}



// TODO Category_Delete should be a function.
$Category_Delete = 'DELETE FROM `'.$Database['Prefix'].'Categories` WHERE `Slug` LIKE \'forum-category-check%\'';
$Category_Delete = mysqli_query($Database['Connection'], $Category_Delete, MYSQLI_STORE_RESULT);
if ( !$Category_Delete ) array_push($Return['Errors'], 'Invalid Query (Member_Delete): '.mysqli_error($Database['Connection']));

echo API_Output($Return);