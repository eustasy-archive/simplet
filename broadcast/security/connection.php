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
	
	<div class="section group">
	</div>

	<h3><a href="https://www.ssllabs.com/ssltest/analyze.html?d=security.simplet.eustasy.org">SSL &amp; TLS</a></h3>
	<p>We get an A+ rating from Qualys SSL Lab for our implementation of Perfect Forward Secrecy, HTTP Strict Transport Security with a long duration, Session resumption, and Strict Transport Security. We've made efforts to remove outdated and vulnerable cipher suites, and prefer TLS over SSL. These result in a PCI compliant and FIPS-ready server.</p>

	<h3><a href="http://spdycheck.org/#security.simplet.eustasy.org">SPDY</a></h3>
	<p>Thanks in part to Nginx (and the community that funded the feature), we support SPDY as a connection method, decreasing the handshake time required while maintaining security.</p>

	<h3><a href="http://ipv6-test.com/validate.php?url=security.simplet.eustasy.org&scheme=https">IPv6</a></h3>
	<p class="textcenter">We support IPv6. Not much else to say about this.</p>

	<?php
	Responses();
	require '../../footer.php';
}
