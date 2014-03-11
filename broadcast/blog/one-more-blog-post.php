<?php

	$Title_HTML = 'One more <em>Blog</em> Post';
	$Title_Plain = 'One more Blog Post';

	$Description_HTML = 'You <strong>can</strong> put HTML in this description.';
	$Description_Plain = 'You shouldn\'t put HTML in this description.';

	$Keywords = 'blog post';

	$Featured_Image = '';

	$Canonical = 'blog/one-more-blog-post';

	$Post_Type = 'Blog Post';
	$Post_Category = 'Padding';

	require_once '../../request.php';

if (substr($Request['path'], 0, strlen($Place['path'].$Canonical)) === $Place['path'].$Canonical) {

	require '../../header.php'; ?>

	<h2>One more Blog Post</h2>
	<p>This one is shorter.</p>

	<?php
	require '../../footer.php';
}
