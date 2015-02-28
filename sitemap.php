<?php

$Page['Title']['Plain'] = 'Sitemap';
$Page['Description']['Plain'] = 'Root Sitemap.';
$Page['Keywords'] = 'sitemap';
$Page['Type'] = 'Sitemap';
$Canonical = '/sitemap';

require_once __DIR__.'/_simplet/request.php';
if ( $Request['Path'] === $Canonical ) {

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
		if ( $Item != basename(__FILE__) ) {

			// Require it
			require $Item;

			// IFRECOGNISE If the Post_Type is Recognized
			if (in_array($Page['Type'], $Post_Types)) {

				// Echo out the Item
				echo '
	<url>
		<loc>'.$Sitewide['Root'].$Canonical.'</loc>
		<lastmod>'.date('Y-m-d', filemtime($Item)).'</lastmod>
		<priority>1</priority>
		<changefreq>daily</changefreq>
	</url>';

			} // IFRECOGNISE

			$Post['Type'] = 'INVALID';

		} // IFNOTTHIS

	} // FOREACH

	// IF CONNECTION
	if ($Database['Connection']) {

		if ($Database['Exists']['Categories']) {

			$Forum_Categories = mysqli_query($Database['Connection'], 'SELECT `Slug`, `Modified` FROM `'.$Database['Prefix'].'Categories` WHERE `Status`=\'Public\' ORDER BY `Modified` DESC', MYSQLI_STORE_RESULT);
			if (!$Forum_Categories) {
				if ( $Sitewide['Debug'] ) {
					echo 'Invalid Query (Forum_Categories): ' . mysqli_error($Database['Connection']);
				}
				// TODO Handle Error
			} else {

				$Forum_Categories_Count = mysqli_num_rows($Forum_Categories);

				if ($Forum_Categories_Count != 0) {
					while ( $Forum_Categories_Fetch = mysqli_fetch_assoc($Forum_Categories) ) {
						// Echo out the Item
						// TODO Support Nice Links
						echo '
		<url>
			<loc>'.$Sitewide['Root'].$Sitewide['Forum'].'?category='.$Forum_Categories_Fetch['Slug'].'</loc>
			<lastmod>'.date('Y-m-d', $Forum_Categories_Fetch['Modified']).'</lastmod>
			<priority>1</priority>
			<changefreq>daily</changefreq>
		</url>';
					}
				}

			}

		}

		if ($Database['Exists']['Topics']) {

			$Forum_Topics = mysqli_query($Database['Connection'], 'SELECT `Slug`, `Modified` FROM `'.$Database['Prefix'].'Topics` WHERE `Status`=\'Public\' ORDER BY `Modified` DESC', MYSQLI_STORE_RESULT);
			if (!$Forum_Topics) {
				if ( $Sitewide['Debug'] ) {
					echo 'Invalid Query (Forum_Topics): ' . mysqli_error($Database['Connection']);
				}
				// TODO Handle Error
			} else {

				$Forum_Topics_Count = mysqli_num_rows($Forum_Topics);

				if ($Forum_Topics_Count != 0) {
					while ( $Forum_Topics_Fetch = mysqli_fetch_assoc($Forum_Topics) ) {
						// Echo out the Item
						// TODO Support Nice Links
						echo '
		<url>
			<loc>'.$Sitewide['Root'].$Sitewide['Forum'].'?topic='.$Forum_Topics_Fetch['Slug'].'</loc>
			<lastmod>'.date('Y-m-d', $Forum_Topics_Fetch['Modified']).'</lastmod>
			<priority>1</priority>
			<changefreq>daily</changefreq>
		</url>';
					}
				}

			}

		}

	} // IF CONNECTION

	// Fin
	echo '
</urlset>';

}