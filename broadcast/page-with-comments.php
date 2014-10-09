<?php

	$Title_HTML = 'A Page with Comments';
	$Title_Plain = 'A Page with Comments';

	$Description_HTML = 'This is a Page with Comments powered by Markdown.';
	$Description_Plain = 'This is a Page with Comments powered by Markdown.';

	$Keywords = 'example page markdown comments';

	$Featured_Image = '';

	$Canonical = 'page-with-comments';

	$Post_Type = 'Page';
	$Post_Category = '';

	require_once __DIR__.'/../simplet/request.php';

if ($Request['path'] === $Place['path'].$Canonical) {
	require '../header.php';
	?>

	<h2>A Page with Comments</h2>
	<p class="textcenter">This is a Page with Comments powered by Markdown.</p>
	<p class="textcenter faded">Try to comment without logging in, or modify or delete a users comment.</p>
	<p class="textcenter faded">You can also try to break out of the comments area, like running a JavaScript alert on the page.</p>

	<?php
	Responses();
	require '../footer.php';
}