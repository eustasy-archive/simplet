<?php

	$Title_HTML = 'Protected Page';
	$Title_Plain = 'Protected Page';

	$Description_HTML = 'An example Protected Page.';
	$Description_Plain = 'An example Protected Page.';

	$Keywords = 'example demo protected page authorization members';

	$Featured_Image = '';

	$Canonical = 'protected-page';

	$Post_Type = 'Protected';
	$Post_Category = '';

	require_once __DIR__.'/../simplet/request.php';

if ($Request['path'] === $Place['path'].$Canonical) {
	require '../header.php';

	if($Member_Auth) {
		echo '
		<h2>Top Secret</h2>
		<p class="textcenter">You are now authorized to view this super secret page.</p>
		<p class="textcenter">Yes, this is it.</p>';

	} else {
		echo '
		<h2>Protected Page</h2>
		<p class="textcenter">Sorry, you need to <a href="account?login&redirect='.urlencode($Canonical).'">log in</a> to view this super secret content.</p>';
	}

	require '../footer.php';
}