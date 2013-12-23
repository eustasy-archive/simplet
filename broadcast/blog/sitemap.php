<?php

	$TextTitle = 'Sitemap';
	$WebTitle = 'Sitemap';
	$Canonical = 'blog/sitemap';
	$PostType = 'Sitemap';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = '';

	require_once '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {


	header('Content-Type: application/xml');
	echo '<?xml version="1.0" encoding="utf-8"?>';

	echo '
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'; // An Sitemap to boot.

	$loop = 0;
	$items = glob('*.php', GLOB_NOSORT);
	array_multisort(array_map('filemtime', $items), SORT_NUMERIC, SORT_DESC, $items);
	foreach($items as $entry) {
		if($entry!='sitemap.php') {
			require $entry;
			if($PostType=='Post'||$PostType=='Page'||$PostType=='Blog'||$PostType=='Forum') {
				$PostLink = $Request['scheme'].'://'.$Request['host'].'/'.$Canonical;
				echo '
		<url>
			<loc>'.$PostLink.'</loc>
			<lastmod>'.date('Y-m-d', filemtime($entry)).'</lastmod>
			<priority>0.9</priority>
			<changefreq>weekly</changefreq>
		</url>';
			}
		}
	}

	echo '
</urlset>';

}
