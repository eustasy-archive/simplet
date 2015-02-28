<?php

$Page['Title']['Plain'] = 'A Blog Post with Comments';
$Page['Description']['Plain'] = 'This is a Blog Post with Comments powered by Markdown.';
$Page['Keywords'] = 'blog post comments markdown';
$Page['Type'] = 'Blog Post';
$Page['Category'] = 'Comment';
$Canonical = '/blog/blog-post-with-comments';

require_once __DIR__.'/../_simplet/request.php';
if ( $Request['Path'] === $Canonical ) {
	require $Templates['Header'];
	?>

	<h2>A Blog Post with Comments</h2>
	<p>This is a Blog Post with Comments powered by Markdown.</p>

	<?php
	Responses();
	require $Templates['Footer'];
}