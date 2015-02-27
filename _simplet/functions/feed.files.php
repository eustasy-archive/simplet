<?php

////	Feed Files Function
//
// Echoes the Comments and Reviews for Files
//
// Forum_Files();

function Feed_Files() {

	global $Canonical, $Database, $Member, $Sitewide;

	// IFEXISTSRESPONSES
	if ( !$Database['Exists']['Responses'] ) {
		return false;
	} else {

		// If Type is set, Encode it.
		if ( isset($_GET['type']) ) {
			$Type = Input_Prepare($_GET['type']);
		} else {
			$Type = false;
		}

		// If the Type is Invalid, set it to false.
		if (
			$Type !== 'Comment' &&
			$Type !== 'Review'
		) {
			$Type = false;
		}

		// Begin building the Query.
		$Query = 'SELECT `Canonical`, `Post`, `Created` FROM `'.$Database['Prefix'].'Responses` WHERE';

		// If type is set only include those, otherwise try both valid types.
		if ( $Type ) {
			$Query .= ' `Type`=\''.$Type.'\' AND';
		} else {
			$Query .= ' (`Type`=\'Comment\' OR `Type`=\'Review\') AND';
		}

		// If a Member, include Private, otherwise just Public.
		if ( $Member['Auth'] ) {
			$Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
		} else {
			$Query .= ' `Status`=\'Public\'';
		}

		// Order by Creation Time.
		$Query .= ' ORDER BY `Created` DESC';

		// TODO Feed Pagination

		// Execute the Query.
		$Query = mysqli_query($Database['Connection'], $Query, MYSQLI_STORE_RESULT);

		// IFQUERY
		if ( !$Query ) {
			if ( $Sitewide['Debug'] ) {
				echo 'Invalid Query (Responses): '.mysqli_error($Database['Connection']);
			}
			return false;
		// IFQUERY
		} else {

			// Send the right header for an RSS Feed
			header('Content-Type: application/rss+xml');

			 // IFCANONICAL: If there's a Canonical passed.
			if ( isset($_GET['canonical']) ) {

				// Encode it and append it to the Query
				$For = urlencode(Input_Prepare($_GET['canonical']));
				$Query .= ' `Canonical`=\''.$For.'\' AND';

				// Send the beginning of the RSS Feed
				echo Feed_Header($Canonical.'?canonical='.$File);

			// If there isn't a Canonical Set.
			} else {

				// Send the beginning of the RSS Feed
				if ( $Type ) {
					echo Feed_Header($Canonical.'?type='.$Type);
				} else {
					echo Feed_Header($Canonical);
				}

			} // IFCANONICAL

			// WHILEFETCH: While there is a new item.
			while ( $Fetch = mysqli_fetch_assoc($Query) ) {

				// Parse HTML with Parsedown.
				$Post = strip_tags(Parsedown::instance()->parse(html_entity_decode($Fetch['Post'], ENT_QUOTES, 'UTF-8')));

				// Create an absolute link.
				$Link = $Sitewide['Root'].urldecode($Fetch['Canonical']);
				// TODO Link should go to right page, after Feed Pagination

				// Echo the RSS Item.
				echo '
		<item>
			<description>'.$Post.'</description>
			<link>'.$Link.'</link>
			<guid>'.$Link.'</guid>
			<pubDate>'.date('r', $Fetch['Created']).'</pubDate>
		</item>';

			} // WHILEFETCH

			// End RSS Output.
			echo '
	</channel>
</rss>';

			return true;

		} // IFQUERY

	} // IFEXISTSRESPONSES

}