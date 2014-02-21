<?php

	$TextTitle = 'One more Blog Post';
	$WebTitle = 'One more Blog Post';
	$Canonical = 'blog/one-more-blog-post';
	$PostType = 'Post';
	$FeaturedImage = '';
	$Description = 'You shouldn\'t put HTML in these descriptions, but you can if you want.';
	$Keywords = 'blog';

	require_once '../../request.php';

if($Request_Path_Entities == $Place['path'].$Canonical) {

	require '../../header.php'; ?>

	<h2>One more Blog Post</h2>
	<p>This one is shorter.</p>

	<?php
	require '../../footer.php';
}