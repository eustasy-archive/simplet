<?php

	$Title_HTML = 'RSS Feed';
	$Title_Plain = 'RSS Feed';

	$Description_HTML = 'Our RSS.';
	$Description_Plain = 'Our RSS.';

	$Keywords = 'rss';

	$Featured_Image = '';

	$Canonical = 'rss';

	$Post_Type = 'Forum RSS';
	$Post_Category = '';

	require_once __DIR__.'/../request.php';

if ($Request['path'] === $Place['path'].$Canonical) {

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

	// IF CONNECTION
	if ($MySQL_Connection) {

		if (isset($_GET['topic']) && !empty($_GET['topic'])) {

			if (Database_Table_Exists('Responses')) {

				$Forum_Responses = mysqli_query($MySQL_Connection, 'SELECT `Slug`, `Modified` FROM `Topics` WHERE `Canonical`=\''.htmlentities($_GET['topic'], ENT_QUOTES, 'UTF-8').'\' `Type`=\'Post\' AND ORDER BY `Modified` DESC', MYSQLI_STORE_RESULT);
				if (!$Forum_Responses) echo 'Invalid Query (Forum_Responses): '.mysqli_error($MySQL_Connection);

				$Forum_Responses_Count = mysqli_num_rows($Forum_Responses);

				if ($Forum_Responses_Count != 0) {
					while($Forum_Responses_Fetch = mysqli_fetch_assoc($Forum_Responses)) {
						// TODO
						echo '
		<item>
			<title>Response to '.$Topic_Title.'</title>
			<description>'.$Response_Snipper.'</description>
			<link>'.$Post_Link.'</link>
			<guid>'.$Post_Link.'</guid>
			<pubDate>'.date('r', $Forum_Responses_Fetch['Modified']).'</pubDate>
		</item>';
					}
				}

			}

		} else if (isset($_GET['forum'])) {

			if (Database_Table_Exists('Topics')) {

				$Forum_Topics_Query = 'SELECT `Slug`, `Title`, `Category`, `Modified` FROM `Topics` WHERE `Status`=\'Public\'';
				if (isset($_GET['category']) && !empty($_GET['category'])) $Forum_Topics_Query .= ' AND `Category`=\''.htmlentities($_GET['category'], ENT_QUOTES, 'UTF-8').'\'';
				$Forum_Topics_Query .= ' ORDER BY `Modified` DESC';

				$Forum_Topics = mysqli_query($MySQL_Connection, $Forum_Topics_Query, MYSQLI_STORE_RESULT);
				if (!$Forum_Topics) echo 'Invalid Query (Forum_Topics): '.mysqli_error($MySQL_Connection);

				$Forum_Topics_Count = mysqli_num_rows($Forum_Topics);

				if ($Forum_Topics_Count != 0) {
					while($Forum_Topics_Fetch = mysqli_fetch_assoc($Forum_Topics)) {
						echo '
		<item>
			<title>'.$Forum_Topics_Fetch['Title'].'</title>
			<description>'.$Forum_Topics_Fetch['Title'].'</description>
			<link>'.$Sitewide_Root.$Sitewide_Forum.'?topic='.$Forum_Topics_Fetch['Slug'].'</link>
			<guid>'.$Sitewide_Root.$Sitewide_Forum.'?topic='.$Forum_Topics_Fetch['Slug'].'</guid>
			<pubDate>'.date('r', $Forum_Topics_Fetch['Modified']).'</pubDate>
		</item>';
					}
				}

			}

		} else {
			// TODO Responses fallback
		}

	} // IF CONNECTION

	// Fin
	echo '
	</channel>
</rss>';
}
