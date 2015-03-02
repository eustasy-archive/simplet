<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

$Backend['root'] = __DIR__.'/../';
$Backend['libs'] = $Backend['root'].'_libs/';
$Backend['simplet'] = $Backend['root'].'_simplet/';
$Backend['onces'] = $Backend['simplet'].'onces/';
$Backend['functions'] = $Backend['simplet'].'functions/';

$Backend['templates'] = $Backend['root'].'_templates/';
$Templates['root'] = $Backend['templates'];
$Templates['Header'] = $Templates['root'].'header.php';
$Templates['Footer'] = $Templates['root'].'footer.php';

////	Require the Configuration
require_once $Backend['simplet'].'config.default.php';
if ( is_readable($Backend['simplet'].'config.custom.php') ) {
	require_once $Backend['simplet'].'config.custom.php';
}

$Backend['Debug'] = true;
$Sitewide['Debug'] = true;

$Return = array();
$Return['Errors'] = array();

$Database = array();
include_once __DIR__.'/config.database.php';
$Database['Prefix'] = 'UNIT_TESTS_';
$Database['Connection'] = mysqli_connect($Database['Host'], $Database['User'], $Database['Pass'], $Database['Name']);
$Database['Error'] = false;
include_once $Backend['functions'].'database.table.exists.php';
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
) {
	include_once $Backend['onces'].'autoinstall.php';
}

$Place = parse_url($Sitewide['Root']);

include_once $Backend['onces'].'cookies.php';
include_once $Backend['onces'].'timezone.php';
include_once $Backend['onces'].'request.php';
include_once $Backend['onces'].'user.php';
include_once $Backend['onces'].'posttypes.php';

include_once $Backend['functions'].'input.prepare.php';
include_once $Backend['functions'].'generator.string.php';

include_once $Backend['functions'].'runonce.create.php';
include_once $Backend['functions'].'runonce.check.php';
include_once $Backend['functions'].'runonce.used.php';
include_once $Backend['functions'].'runonce.delete.php';

include_once $Backend['functions'].'runonce.csrf.create.php';
include_once $Backend['functions'].'runonce.csrf.check.php';

include_once $Backend['onces'].'csrfprotect.php';

include_once $Backend['functions'].'api.output.php';