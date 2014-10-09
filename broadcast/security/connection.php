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
	<a href="https://www.ssllabs.com/ssltest/analyze.html?d=security.simplet.eustasy.org">https://www.ssllabs.com/ssltest/analyze.html?d=security.simplet.eustasy.org</a>

	<h3>SPDY</h3>
	<a href="http://spdycheck.org/#security.simplet.eustasy.org">http://spdycheck.org/#security.simplet.eustasy.org</a>

	<h3>IPv6</h3>
	<a href="http://ipv6-test.com/validate.php?url=security.simplet.eustasy.org&scheme=https">http://ipv6-test.com/validate.php?url=security.simplet.eustasy.org&scheme=https</a>

	<?php
	Responses();
	require '../../footer.php';
}
