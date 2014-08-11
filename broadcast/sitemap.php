<?php

	$Title_HTML = 'Sitemap';
	$Title_Plain = 'Sitemap';

	$Description_HTML = 'Root Sitemap.';
	$Description_Plain = 'Root Sitemap.';

	$Keywords = 'sitemap';

	$Featured_Image = '';

	$Canonical = 'sitemap';

	$Post_Type = 'Sitemap';
	$Post_Category = '';

	require_once __DIR__.'/../request.php';

if ($Request['path'] === $Place['path'].$Canonical) {

	// Send the right header for a Sitemap
	header('Content-Type: application/xml');

	// Set the encoding and doctype
	echo '<?xml version="1.0" encoding="utf-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	// List all the files
	$Items = globRecursive('*.php');

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

				// Echo out the Item
				echo '
	<url>
		<loc>'.$Sitewide_Root.$Canonical.'</loc>
		<lastmod>'.date('Y-m-d', filemtime($Item)).'</lastmod>
		<priority>1</priority>
		<changefreq>daily</changefreq>
	</url>';

			} // IFRECOGNISE

			$Post_Type = 'INVALID';

		} // IFNOTTHIS

	} // FOREACH

	// IF CONNECTION
	if ($Database['Connection']) {

		if (Database_Table_Exists('Categories')) {

			$Forum_Categories = mysqli_query($Database['Connection'], 'SELECT `Slug`, `Modified` FROM `Categories` WHERE `Status`=\'Public\' ORDER BY `Modified` DESC', MYSQLI_STORE_RESULT);
			if (!$Forum_Categories) echo 'Invalid Query (Forum_Categories): '.mysqli_error($Database['Connection']);

			$Forum_Categories_Count = mysqli_num_rows($Forum_Categories);

			if ($Forum_Categories_Count != 0) {
				while($Forum_Categories_Fetch = mysqli_fetch_assoc($Forum_Categories)) {
					// Echo out the Item
					echo '
	<url>
		<loc>'.$Sitewide_Root.$Sitewide_Forum.'?category='.$Forum_Categories_Fetch['Slug'].'</loc>
		<lastmod>'.date('Y-m-d', $Forum_Categories_Fetch['Modified']).'</lastmod>
		<priority>1</priority>
		<changefreq>daily</changefreq>
	</url>';
				}
			}

		}

		if (Database_Table_Exists('Topics')) {

			$Forum_Topics = mysqli_query($Database['Connection'], 'SELECT `Slug`, `Modified` FROM `Topics` WHERE `Status`=\'Public\' ORDER BY `Modified` DESC', MYSQLI_STORE_RESULT);
			if (!$Forum_Topics) echo 'Invalid Query (Forum_Topics): '.mysqli_error($Database['Connection']);

			$Forum_Topics_Count = mysqli_num_rows($Forum_Topics);

			if ($Forum_Topics_Count != 0) {
				while($Forum_Topics_Fetch = mysqli_fetch_assoc($Forum_Topics)) {
					// Echo out the Item
					echo '
	<url>
		<loc>'.$Sitewide_Root.$Sitewide_Forum.'?topic='.$Forum_Topics_Fetch['Slug'].'</loc>
		<lastmod>'.date('Y-m-d', $Forum_Topics_Fetch['Modified']).'</lastmod>
		<priority>1</priority>
		<changefreq>daily</changefreq>
	</url>';
				}
			}

		}

	} // IF CONNECTION

	// Fin
	echo '
</urlset>';

}
