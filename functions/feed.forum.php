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
		$Feed_Query = 'SELECT `Canonical`, `Post`, `Created` FROM `Responses` WHERE `Canonical`=\''.$Topic.'\' AND';
		if ($Member_Auth) $Feed_Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
		else $Feed_Query .= ' `Status`=\'Public\'';
		$Feed_Query .= ' ORDER BY `Created` DESC';

		$Responses = mysqli_query($MySQL_Connection, $Feed_Query, MYSQLI_STORE_RESULT);
		if (!$Responses) exit('Invalid Query (Responses): '.mysqli_error($MySQL_Connection));

		while($Responses_Fetch = mysqli_fetch_assoc($Responses)) {
			$Post = strip_tags(Parsedown::instance()->parse(html_entity_decode($Responses_Fetch['Post'], ENT_QUOTES, 'UTF-8')));
			// TODO Link should go to right page, after Feed Pagination
			$Link = $Sitewide_Root.$Canonical.'?topic='.$Responses_Fetch['Canonical'];
			echo '
			<item>
				<description>'.$Post.'</description>
				<link>'.$Link.'</link>
				<guid>'.$Link.'</guid>
				<pubDate>'.date('r', $Responses_Fetch['Created']).'</pubDate>
			</item>';
		}

	} else {

		if (isset($_GET['category'])) $Category = htmlentities($_GET['category'], ENT_QUOTES, 'UTF-8');
		else $Category = false;

		// Send the beginning of the RSS Feed
		if ($Category) Feed_Header($Canonical.'?feed&category='.$Category);
		else Feed_Header($Canonical.'?feed');

		// TODO Feed Pagination
		$Feed_Query = 'SELECT `Slug`, `Title`, `Created` FROM `Topics` WHERE';
		if ($Category) $Feed_Query .= ' `Category`=\''.$Category.'\' AND';
		if ($Member_Auth) $Feed_Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
		else $Feed_Query .= ' `Status`=\'Public\'';
		$Feed_Query .= ' ORDER BY `Created` DESC';

		$Topics = mysqli_query($MySQL_Connection, $Feed_Query, MYSQLI_STORE_RESULT);
		if (!$Topics) exit('Invalid Query (Topics): '.mysqli_error($MySQL_Connection));

		while($Topics_Fetch = mysqli_fetch_assoc($Topics)) {
			$Title = strip_tags(html_entity_decode($Topics_Fetch['Title'], ENT_QUOTES, 'UTF-8'));
			$Link = $Sitewide_Root.$Canonical.'?topic='.$Topics_Fetch['Slug'];
			echo '
			<item>
				<title>'.$Title.'</title>
				<link>'.$Link.'</link>
				<guid>'.$Link.'</guid>
				<pubDate>'.date('r', $Topics_Fetch['Created']).'</pubDate>
			</item>';
		}

	}

	echo '
	</channel>
</rss>';

}
