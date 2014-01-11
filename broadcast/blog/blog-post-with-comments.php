<?php

	$TextTitle = 'A Blog Post with Comments';
	$WebTitle = 'A Blog Post with Comments';
	$Canonical = 'blog/blog-post-with-comments';
	$PostType = 'Post';
	$FeaturedImage = '';
	$Description = 'This is a Blog Post with Comments powered by Markdown.';
	$Keywords = 'blog';

	require_once '../../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

	require '../../header.php'; ?>

	<h2>A Blog Post with Comments</h2>
	<p>This is a Blog Post with Comments powered by Markdown.</p>

<?php
	require '../../comments.php';
	require '../../footer.php';
}
