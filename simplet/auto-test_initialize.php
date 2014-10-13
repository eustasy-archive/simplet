<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include_once __DIR__.'/config.php';
$Sitewide_Debug = true;

$Return = array();
$Return['Errors'] = array();

$Database = array();
include_once __DIR__.'/config.database.php';
$Database['Prefix'] = 'UNIT_TESTS_';
$Database['Connection'] = mysqli_connect($Database['Host'], $Database['User'], $Database['Pass'], $Database['Name']);
$Database['Error'] = false;
include_once __DIR__.'/functions/database.table.exists.php';
$Database['Exists'] = array();
$Database['Exists']['Members'] = Database_Table_Exists('UNIT_TESTS_Members', false);
$Database['Exists']['Sessions'] = Database_Table_Exists('UNIT_TESTS_Sessions', false);
$Database['Exists']['Failures'] = Database_Table_Exists('UNIT_TESTS_Failures', false);
$Database['Exists']['Runonce'] = Database_Table_Exists('UNIT_TESTS_Runonce', false);
$Database['Exists']['Settings'] = Database_Table_Exists('UNIT_TESTS_Settings', false);
$Database['Exists']['Categories'] = Database_Table_Exists('UNIT_TESTS_Categories', false);
$Database['Exists']['Topics'] = Database_Table_Exists('UNIT_TESTS_Topics', false);
$Database['Exists']['Responses'] = Database_Table_Exists('UNIT_TESTS_Responses', false);
$Database['Exists']['Helpfulness'] = Database_Table_Exists('UNIT_TESTS_Helpfulness', false);
$Database['Exists']['Views'] = Database_Table_Exists('UNIT_TESTS_Views', false);
$Database['Prefix'] = 'UNIT_TESTS_';
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
) include_once __DIR__.'/onces/autoinstall.php';

$Place = parse_url($Sitewide_Root);
$Request = parse_url($Place['scheme'].'://'.$Place['host'].$_SERVER['REQUEST_URI']);
$Time = time();
$Time_15mins = $Time+900;
$Time_1hour = $Time+3600;
$Cookie_Session = str_replace( '.', '_', $Place['host']).'_session';
include_once __DIR__.'/functions/input.prepare.php';
$User_IP = Input_Prepare($_SERVER['REMOTE_ADDR']);
$Post_Types = array('Page', 'Blog', 'Blog Index', 'Blog Category', 'Blog Post', 'Forum', 'Forum Index', 'Forum Category', 'Forum Topic');

include_once __DIR__.'/functions/api.output.php';