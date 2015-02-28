<?php

$Page['Title']['Plain'] = 'Administration';
$Page['Description']['Plain'] = 'Administration';
$Page['Keywords'] = 'administration control manage management password email user';
$Page['Type'] = 'Page';
$Canonical = '/administration';

require_once __DIR__.'/_simplet/request.php';
if ( $Request['Path'] === $Canonical ) {

	// Canonical may change later, Administration won't.
	$Administration = $Canonical;
	$Lib_Browning_Config = __DIR__.'/../libs/Browning_Config.php';
	$Lib_Browning_Send = __DIR__.'/../libs/Browning_Send.php';

	// Unauthenticated
	if ( !$Member['Admin'] ) {
		header('Location: '.$Sitewide['Account'], true, 302);
		exit;

	// Key Set
	} else if (isset($_GET['key'])) {
		$Keywords = 'administration password email';
		require $Templates['Header'];
		echo htmlentites($_GET['key'], ENT_QUOTES, 'UTF-8');
		require $Templates['Footer'];

	// Index
	} else {
		require $Templates['Header'];
		echo '<h2>Administration</h2>';
		require $Templates['Footer'];
	}
}