<?php

	$Title_HTML = 'A Blog Post with Comments';
	$Title_Plain = 'A Blog Post with Comments';
	
	$Description_HTML = 'This is a Blog Post with Comments powered by Markdown.';
	$Description_Plain = 'This is a Blog Post with Comments powered by Markdown.';
	
	$Keywords = 'blog post comments markdown';
	
	$Featured_Image = '';
	
	$Canonical = 'blog/blog-post-with-comments';
	
	$Post_Type = 'Blog Post';
	$Post_Category = 'Comment';

	require_once __DIR__.'/../../request.php';

if ($Request['path'] === $Place['path'].$Canonical) {

	require '../../header.php'; ?>

	<h2>A Blog Post with Comments</h2>
	<p>This is a Blog Post with Comments powered by Markdown.</p>

	<?php
	Responses();
	require '../../footer.php';
}
