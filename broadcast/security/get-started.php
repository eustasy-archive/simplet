<?php

	$Title_HTML = 'Get Started';
	$Title_Plain = 'Get Started';

	$Description_HTML = 'Get Started';
	$Description_Plain = 'Get Started';

	$Keywords = 'get started simplet security';

	$Featured_Image = '';

	$Canonical = 'security/get-started';

	$Post_Type = 'Page';
	$Post_Category = '';

	require_once __DIR__.'/../../simplet/request.php';

if ($Request['path'] === $Place['path'].$Canonical) {
	require '../../header.php';
	?>

	<h2>Get Started</h2>

	<h3>Step 1. Register.</h3>
	<p>We'd recommend you start by registering, possibly with a throw-away email address (there's a serious chance it could get discovered).</p>

	<h3>Step 2. Read the Rules.</h3>
	<p><a href="https://security.simplet.eustasy.org/security/responsible-disclosure">The Rules</a> are there to keep everyone safe and happy while we work.</p>

	<h3>Step 3. Learn the Levels.</h3>
	<p><a href="https://security.simplet.eustasy.org/security/levels">The Levels</a> classify how bad we think a security bug is, and whether or not there may be any reward for it.</p>

	<h3>Step 4. Check out the Source.</h3>
	<p><a href="https://github.com/eustasy/simplet">The source code of Simplet</a> is made publicly available on GitHub in the MIT License. You can browse, prod and poke it to your hearts content.</p>

	<h3>Step 5. Discuss and report issues.</h3>
	<p><a href="https://security.simplet.eustasy.org/forum">The Forum</a> is the place for discussing your breaks and attacks. Use the private category for any potentially sensitive information.</p>

	<?php
	Responses();
	require '../../footer.php';
}