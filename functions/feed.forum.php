<?php

// ### Feed Forum Function ###
//
// Echos the Forum Topics available to the user as an RSS Feed.
//
// Forum_Forum();

function Feed_Forum() {

	// Set some Globals
	global $MySQL_Connection, $Member_Auth, $Sitewide_Root, $Canonical;

	if (isset($_GET['topic'])) {

		$Topic = htmlentities($_GET['topic'], ENT_QUOTES, 'UTF-8');

		// Send the beginning of the RSS Feed
		Feed_Header($Canonical.'?feed&topic='.$Topic);

		// TODO Feed Pagination
		$Query = 'SELECT `Canonical`, `Post`, `Created` FROM `Responses` WHERE `Canonical`=\''.$Topic.'\' AND';
		if ($Member_Auth) $Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
		else $Query .= ' `Status`=\'Public\'';
		$Query .= ' ORDER BY `Created` DESC';

		$Query = mysqli_query($MySQL_Connection, $Query, MYSQLI_STORE_RESULT);
		if (!$Query) exit('Invalid Query (Responses): '.mysqli_error($MySQL_Connection));

		while($Fetch = mysqli_fetch_assoc($Query)) {
			$Post = strip_tags(Parsedown::instance()->parse(html_entity_decode($Fetch['Post'], ENT_QUOTES, 'UTF-8')));
			// TODO Link should go to right page, after Feed Pagination
			$Link = $Sitewide_Root.$Canonical.'?topic='.$Fetch['Canonical'];
			echo '
			<item>
				<description>'.$Post.'</description>
				<link>'.$Link.'</link>
				<guid>'.$Link.'</guid>
				<pubDate>'.date('r', $Fetch['Created']).'</pubDate>
			</item>';
		}

	} else {

		if (isset($_GET['category'])) $Category = htmlentities($_GET['category'], ENT_QUOTES, 'UTF-8');
		else $Category = false;

		// Send the beginning of the RSS Feed
		if ($Category) Feed_Header($Canonical.'?feed&category='.$Category);
		else Feed_Header($Canonical.'?feed');

		// TODO Feed Pagination
		$Query = 'SELECT `Slug`, `Title`, `Created` FROM `Topics` WHERE';
		if ($Category) $Query .= ' `Category`=\''.$Category.'\' AND';
		if ($Member_Auth) $Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
		else $Query .= ' `Status`=\'Public\'';
		$Query .= ' ORDER BY `Created` DESC';

		$Query = mysqli_query($MySQL_Connection, $Query, MYSQLI_STORE_RESULT);
		if (!$Query) exit('Invalid Query (Topics): '.mysqli_error($MySQL_Connection));

		while($Fetch = mysqli_fetch_assoc($Query)) {
			$Title = $Fetch['Title'];
			$Link = $Sitewide_Root.$Canonical.'?topic='.$Fetch['Slug'];
			echo '
			<item>
				<title>'.$Title.'</title>
				<link>'.$Link.'</link>
				<guid>'.$Link.'</guid>
				<pubDate>'.date('r', $Fetch['Created']).'</pubDate>
			</item>';
		}

	}

	echo '
	</channel>
</rss>';

}
