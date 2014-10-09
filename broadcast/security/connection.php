<?php

	$Title_HTML = 'Connection';
	$Title_Plain = 'Connection';

	$Description_HTML = 'Connection';
	$Description_Plain = 'Connection';

	$Keywords = 'connection tls ssl ipv6 spdy';

	$Featured_Image = '';

	$Canonical = 'security/connection';

	$Post_Type = 'Page';
	$Post_Category = '';

	require_once __DIR__.'/../../simplet/request.php';

if ($Request['path'] === $Place['path'].$Canonical) {
	require '../../header.php';
	?>

	<h2>Connection</h2>

	<h3>SSL &amp; TLS</h3>

	<h3>SPDY</h3>

	<h3>IPv6</h3>

	<?php
	Responses();
	require '../../footer.php';
}
