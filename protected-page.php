<?php

$Page['Title']['HTML'] = 'Protected Page';
$Page['Title']['Plain'] = 'Protected Page';
$Page['Description']['HTML'] = 'An example Protected Page.';
$Page['Description']['Plain'] = 'An example Protected Page.';
$Page['Keywords'] = 'example demo protected page authorization members';
$Page['Featured Image'] = '';
$Page['Type'] = 'Protected';
$Page['Category'] = '';
$Canonical = '/protected-page';

require_once __DIR__.'/_simplet/request.php';
if ( $Request['Path'] === $Canonical ) {
	require $Templates['Header'];

	if ( $Member['Auth'] ) {
		echo '
		<h2>Top Secret</h2>
		<p class="textcenter">You are now authorized to view this super secret page.</p>
		<p class="textcenter">Yes, this is it.</p>';

	} else {
		echo '
		<h2>Protected Page</h2>
		<p class="textcenter">Sorry, you need to <a href="account?login&redirect='.urlencode($Canonical).'">log in</a> to view this super secret content.</p>';
	}

	require $Templates['Footer'];
}