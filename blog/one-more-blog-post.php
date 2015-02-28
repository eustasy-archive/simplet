<?php

$Page['Title']['HTML'] = 'One more <em>Blog</em> Post';
$Page['Title']['Plain'] = 'One more Blog Post';
$Page['Description']['HTML'] = 'You <strong>can</strong> put HTML in this description.';
$Page['Description']['Plain'] = 'You shouldn\'t put HTML in this description.';
$Page['Keywords'] = 'blog post';
$Page['Type'] = 'Blog Post';
$Page['Category'] = 'Padding';
$Canonical = '/blog/one-more-blog-post';

require_once __DIR__.'/../_simplet/request.php';
if ( $Request['Path'] === $Canonical ) {
	require $Templates['Header'];
	?>

	<h2>One more Blog Post</h2>
	<p>This one is shorter.</p>

	<?php
	require $Templates['Footer'];
}