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

	require_once '../request.php';

if (substr($Request['path'], 0, strlen($Place['path'].$Canonical)) === $Place['path'].$Canonical) {

	require '../header.php'; ?>

	<h2>A Page with Comments</h2>
	<p>This is a Page with Comments powered by Markdown.</p>

<?php
	Responses();
	require '../footer.php';
}
