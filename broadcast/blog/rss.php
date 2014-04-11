<?php

	$Title_HTML = 'RSS Feed';
	$Title_Plain = 'RSS Feed';

	$Description_HTML = 'Our RSS.';
	$Description_Plain = 'Our RSS.';

	$Keywords = 'blog posts rss';

	$Featured_Image = '';

	$Canonical = 'blog/rss';

	$Post_Type = 'Blog RSS';
	$Post_Category = '';

	require_once __DIR__.'/../../request.php';

if ($Request['path'] === $Place['path'].$Canonical) {

	Feed_Header($Canonical);

	// List all the files
	$Items = glob('*.php', GLOB_NOSORT);

	// Order them by time
	array_multisort(array_map('filemtime', $Items), SORT_NUMERIC, SORT_DESC, $Items);

	// FOREACH: For each Item
	// TODO Feed Pagination
	foreach ($Items as $Item) {

		// IFNOTTHIS: So long as it isn't this file
		if ($Item != basename(__FILE__)) {

			// Require it
			require $Item;

			// IFPOST If it is a post (and hence has a time)
			if ($Post_Type == 'Blog Post') {

				// Make the link
				$Post_Link = $Sitewide_Root.$Canonical;

				// Echo out the Item
				echo '
		<item>
			<title>'.$Title_Plain.'</title>
			<description>'.$Description_Plain.'</description>
			<link>'.$Post_Link.'</link>
			<guid>'.$Post_Link.'</guid>
			<pubDate>'.date('r', filemtime($Item)).'</pubDate>
		</item>';

			} // IFPOST

		} // IFNOTTHIS

	} // FOREACH

	// Fin
	echo '
	</channel>
</rss>';
}
