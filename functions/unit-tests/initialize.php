<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include __DIR__.'/../../config.php';
$Sitewide_Debug = true;

$Return = array();
$Return['Errors'] = array();

$Place = parse_url($Sitewide_Root);
$Request = parse_url($Place['scheme'].'://'.$Place['host'].$_SERVER['REQUEST_URI']);
$Time = time();
$Time_15mins = $Time+900;
$Time_1hour = $Time+3600;
$Cookie_Session = str_replace( '.', '_', $Place['host']).'_session';
$Post_Types = array('Page', 'Blog', 'Blog Index', 'Blog Category', 'Blog Post', 'Forum', 'Forum Index', 'Forum Category', 'Forum Topic');

$Database = array();
include __DIR__.'/../../config.database.php';
$Database['Prefix'] = 'UNIT_TESTS_';
$Database['Connection'] = mysqli_connect($Database['Host'], $Database['User'], $Database['Pass'], $Database['Name']);
$Database['Error'] = false;
require __DIR__.'/../database.table.exists.php';
$Database['Exists'] = array();
$Database['Exists']['Members'] = Database_Table_Exists('Members');
$Database['Exists']['Sessions'] = Database_Table_Exists('Sessions');
$Database['Exists']['Failures'] = Database_Table_Exists('Failures');
$Database['Exists']['Runonce'] = Database_Table_Exists('Runonce');
$Database['Exists']['Settings'] = Database_Table_Exists('Settings');
$Database['Exists']['Categories'] = Database_Table_Exists('Categories');
$Database['Exists']['Topics'] = Database_Table_Exists('Topics');
$Database['Exists']['Responses'] = Database_Table_Exists('Responses');
$Database['Exists']['Helpfulness'] = Database_Table_Exists('Helpfulness');
$Database['Exists']['Views'] = Database_Table_Exists('Views');
if (
	!$Database['Exists']['Members'] ||
	!$Database['Exists']['Sessions'] ||
	!$Database['Exists']['Failures'] ||
	!$Database['Exists']['Runonce'] ||
	!$Database['Exists']['Categories'] ||
	!$Database['Exists']['Topics'] ||
	!$Database['Exists']['Responses'] ||
	!$Database['Exists']['Helpfulness'] ||
	!$Database['Exists']['Views']
) require __DIR__.'/../../onces/autoinstall.php';

require '../api.output.php';