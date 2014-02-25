<?php

	$TextTitle = 'RSS Feed';
	$WebTitle = 'RSS Feed';
	$Canonical = 'blog/rss';
	$PostType = 'RSS';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = '';

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
			if ($PostType == 'Post') {

				// Make the link
				$PostLink = $Sitewide_Root.$Canonical;

				// Echo out the Item
				echo '
		<item>
			<title>'.$WebTitle.'</title>
			<description>'.$Description.'</description>
			<link>'.$PostLink.'</link>
			<guid>'.$PostLink.'</guid>
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
