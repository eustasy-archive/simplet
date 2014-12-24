<?php

	$Title_HTML = 'Administration';
	$Title_Plain = 'Administration';

	$Description_HTML = 'Administration';
	$Description_Plain = 'Administration';

	$Keywords = 'administration password email';

	$Featured_Image = '';

	$Canonical = 'administration';

	$Post_Type = 'Page';
	$Post_Category = '';

	require_once __DIR__.'/../simplet/request.php';

	$Administration = $Canonical; // Canonical may change later, Account won't
	$Header = '../header.php';
	$Footer = '../footer.php';
	$Lib_Browning_Config = __DIR__.'/../libs/Browning_Config.php';
	$Lib_Browning_Send = __DIR__.'/../libs/Browning_Send.php';

if ($Request['path'] === $Place['path'].$Canonical) {

	if (!$Member_Auth) { // Login Redirect
		header('Location: '.$Place['path'].'account', true, 302);
		die();

	} else if (isset($_GET['key'])) {

		$Title_HTML = 'Administration';
		$Title_Plain = 'Administration';

		$Description_HTML = 'Administration';
		$Description_Plain = 'Administration';

		$Keywords = 'administration password email';

		$Canonical = 'administration';

		require $Header;

		require $Footer;

	} else {

		require $Header;

		echo '<h2>Administration</h2>';

		require $Footer;

	}
}