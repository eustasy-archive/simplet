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

	require_once '../../request.php';

if ($Request_Path_Entities == $Place['path'].$Canonical) {

	// Send the right header for an RSS Feed
	header('Content-Type: application/rss+xml');

	// Set the doctype and some basic information
	echo '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<atom:link href="'.$Sitewide_Root.$Canonical.'" rel="self" type="application/rss+xml" />
		<title>'.$Sitewide_Title.'</title>
		<description>'. $Sitewide_Tagline.'</description>
		<link>'. $Sitewide_Root.'</link>
		<language>en</language>
		<generator>Simplet</generator>';

	// List all the files
	$Items = glob('*.php', GLOB_NOSORT);

	// Order them by time
	array_multisort(array_map('filemtime', $Items), SORT_NUMERIC, SORT_DESC, $Items);

	// FOREACH: For each Item
	foreach ($Items as $Item) {

		// IFNOTTHIS: So long as it isn't this file
		if ($Item != 'rss.php') {

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
