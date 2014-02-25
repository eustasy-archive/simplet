<?php

	$TextTitle = 'Sitemap';
	$WebTitle = 'Sitemap';
	$Canonical = 'blog/sitemap';
	$PostType = 'Sitemap';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = '';

	require_once '../../request.php';

if ($Request_Path_Entities == $Place['path'].$Canonical) {

	// Send the right header for a Sitemap
	header('Content-Type: application/xml');

	// Set the encoding and doctype
	echo '<?xml version="1.0" encoding="utf-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	// List all the files
	$Items = glob('*.php', GLOB_NOSORT);

	// Order them by time
	array_multisort(array_map('filemtime', $Items), SORT_NUMERIC, SORT_DESC, $Items);

	// FOREACH: For each Item
	foreach ($Items as $Item) {

		// IFNOTTHIS: So long as it isn't this file
		if ($Item != 'sitemap.php') {

			// Require it
			require $Item;

			// IFRECOGNISE If the PostType is Recognised
			if ($PostType == 'Index' || $PostType=='Post' || $PostType=='Page' || $PostType=='Blog' || $PostType=='Forum') {

				// Make the link
				$PostLink = $Sitewide_Root.$Canonical;

				// Echo out the Item
				echo '
	<url>
		<loc>'.$PostLink.'</loc>
		<lastmod>'.date('Y-m-d', filemtime($entry)).'</lastmod>
		<priority>0.9</priority>
		<changefreq>weekly</changefreq>
	</url>';

			} // IFRECOGNISE

		} // IFNOTTHIS

	} // FOREACH

	// Fin
	echo '
</urlset>';

}
