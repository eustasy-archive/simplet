<?php

	$TextTitle = 'RSS Feed';
	$WebTitle = 'RSS Feed';
	$Canonical = 'blog/rss';
	$PostType = 'RSS';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = '';

	require_once '../../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

	header('Content-Type: application/rss+xml');
	echo '<?xml version="1.0" encoding="utf-8"?>
';

?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<atom:link href="<?php echo $Sitewide_Root.$Canonical; ?>" rel="self" type="application/rss+xml" />
		<title><?php echo $Sitewide_Title; ?></title>
		<description><?php echo $Sitewide_Tagline; ?></description>
		<link><?php echo $Sitewide_Root; ?></link>
		<language>en</language>
		<generator>Simplet</generator>
		<?php
			$loop = 0;
			$items = glob('*.php', GLOB_NOSORT);
			array_multisort(array_map('filemtime', $items), SORT_NUMERIC, SORT_DESC, $items);
			foreach($items as $entry) {
				if($entry!='rss.php') {
					require $entry;
					if($PostType=='Post') {
						$PostLink = $Sitewide_Root.$Canonical;
						echo '
		<item>
			<title>'.$WebTitle.'</title>
			<description>'.$Description.'</description>
			<link>'.$PostLink.'</link>
			<guid>'.$PostLink.'</guid>
			<pubDate>'.date('r', filemtime($entry)).'</pubDate>
		</item>';
					}
				}
			}
		?>
	</channel>
</rss><?php
}