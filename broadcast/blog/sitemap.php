<?php

	$Title_HTML = 'Sitemap';
	$Title_Plain = 'Sitemap';

	$Description_HTML = 'Our Sitemap.';
	$Description_Plain = 'Our Sitemap.';

	$Keywords = 'sitemap';

	$Featured_Image = '';

	$Canonical = 'blog/sitemap';

	$Post_Type = 'Sitemap';
	$Post_Category = '';

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
		if ($Item != basename(__FILE__)) {

			// Require it
			require $Item;

			// IFRECOGNISE If the Post_Type is Recognized
			if (in_array($Post_Type, $Post_Types)) {

				// Make the link
				$Post_Link = $Sitewide_Root.$Canonical;

				// Echo out the Item
				echo '
	<url>
		<loc>'.$Post_Link.'</loc>
		<lastmod>'.date('Y-m-d', filemtime($Item)).'</lastmod>
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
