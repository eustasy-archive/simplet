<?php

	$Title_HTML = 'RSS Feed';
	$Title_Plain = 'RSS Feed';

	$Description_HTML = 'Our RSS.';
	$Description_Plain = 'Our RSS.';

	$Keywords = 'rss';

	$Featured_Image = '';

	$Canonical = 'feed';

	$Post_Type = 'RSS';
	$Post_Category = '';

	require_once __DIR__.'/../request.php';

if ($Request['path'] === $Place['path'].$Canonical) {

	Feed_Files();

}