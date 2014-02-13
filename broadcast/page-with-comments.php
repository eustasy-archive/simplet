<?php

	$TextTitle = 'A Page with Comments';
	$WebTitle = 'A Page with Comments';
	$Canonical = 'page-with-comments';
	$PostType = 'Page';
	$FeaturedImage = '';
	$Description = 'This is a Page with Comments powered by Markdown.';
	$Keywords = 'page comments markdown';

	require_once '../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

	require '../header.php'; ?>

	<h2>A Page with Comments</h2>
	<p>This is a Page with Comments powered by Markdown.</p>

<?php
	require '../responses.php';
	Responses();
	require '../footer.php';
}
