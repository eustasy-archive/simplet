<?php

// ### Feed Files Function ###
//
// Echos the Comments and Reviews for Files
//
// Forum_Files();

function Feed_Files() {

	// Set some Globals
	global $MySQL_Connection, $Member_Auth, $Sitewide_Root, $Canonical;

	if (isset($_GET['type'])) $Type = htmlentities($_GET['type'], ENT_QUOTES, 'UTF-8');
	else $Type = false;

	if ($Type !== 'Comment' && $Type !== 'Review') $Type = false;

	$Query = 'SELECT `Canonical`, `Post`, `Created` FROM `Responses` WHERE';

	if (isset($_GET['canonical'])) {

		$For = urlencode(htmlentities($_GET['canonical'], ENT_QUOTES, 'UTF-8'));

		// Send the beginning of the RSS Feed
		Feed_Header($Canonical.'?canonical='.$File);

		$Query .= ' `Canonical`=\''.$For.'\' AND';

	} else {

		// Send the beginning of the RSS Feed
		if ($Type) Feed_Header($Canonical.'?type='.$Type);
		else Feed_Header($Canonical);

	}

	if ($Type) $Query .= ' `Type`=\''.$Type.'\' AND';
	else $Query .= ' (`Type`=\'Comment\' OR `Type`=\'Review\') AND';
	if ($Member_Auth) $Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
	else $Query .= ' `Status`=\'Public\'';
	$Query .= ' ORDER BY `Created` DESC';
	// TODO Feed Pagination

	$Query = mysqli_query($MySQL_Connection, $Query, MYSQLI_STORE_RESULT);
	if (!$Query) exit('Invalid Query (Responses): '.mysqli_error($MySQL_Connection));

	while($Fetch = mysqli_fetch_assoc($Query)) {
		$Post = strip_tags(Parsedown::instance()->parse(html_entity_decode($Fetch['Post'], ENT_QUOTES, 'UTF-8')));
		// TODO Link should go to right page, after Feed Pagination
		$Link = $Sitewide_Root.urldecode($Fetch['Canonical']);
		echo '
		<item>
			<description>'.$Post.'</description>
			<link>'.$Link.'</link>
			<guid>'.$Link.'</guid>
			<pubDate>'.date('r', $Fetch['Created']).'</pubDate>
		</item>';
	}

	echo '
	</channel>
</rss>';

}
