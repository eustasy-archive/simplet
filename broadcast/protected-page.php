<?php

	$TextTitle = 'Protected Page';
	$WebTitle = 'Protected Page';
	$Canonical = 'protected-page';
	$PostType = 'Protected';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'protected example page default styles';

	require_once '../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

	require '../header.php';

	if($Member_Auth) {

		echo '
		<h2>Top Secret</h2>
		<p class="textcenter">You are now authorized to view this super secret page.</p>
		<p class="textcenter">Yes, this is it.</p>';

	} else {

		echo '
		<h2>Protected Page</h2>
		<p class="textcenter">Sorry, you need to log in to view this super secret content.</p>';

	}

	require '../footer.php';

} ?>
