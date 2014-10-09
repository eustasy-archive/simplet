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
		?>
		<h2>Top Secret</h2>
		<p class="textcenter">You are now authorized to view this super secret page.</p>
		<p class="textcenter">Yes, this is it.</p>
		<p class="textcenter faded">You appear to be logged in.</p>
		<?php

	} else {
		?>
		<h2>Protected Page</h2>
		<p class="textcenter faded">This is a protected page. Try to view it's content without logging in.</p>
		<p class="textcenter">Sorry, you need to <a href="account?login&redirect=<?php echo urlencode($Canonical); ?>">log in</a> to view this super secret content.</p>
		<?php
	}

	require '../footer.php';
}