<?php

////	Feed Forum Function
//
// Lists Responses or Forum Topics as an RSS Feed
//
// Feed_Forum();
// Feed_Forum(); ?category=introductions
// Feed_Forum(); ?topic=hello-world

function Feed_Forum() {

	global $Canonical, $Database, $Member_Auth, $Sitewide_Root;

	// IFTOPIC
	if ( isset($_GET['topic']) ) {

		// IFEXISTSRESPONSES
		if ( !$Database['Exists']['Responses'] ) return false;
		else {

			// Get the Topic
			$Topic = Input_Prepare($_GET['topic']);

			// Send the right header for an RSS Feed
			header('Content-Type: application/rss+xml');

			// Send the beginning of the RSS Feed
			echo Feed_Header($Canonical.'?feed&topic='.$Topic);

			// Assemble Query
			// TODO Feed Pagination
			$Query = 'SELECT `Canonical`, `Post`, `Created` FROM `'.$Database['Prefix'].'Responses` WHERE `Canonical`=\''.$Topic.'\' AND';
			if ($Member_Auth) $Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
			else $Query .= ' `Status`=\'Public\'';
			$Query .= ' ORDER BY `Created` DESC';

			// Execute the query
			$Query = mysqli_query($Database['Connection'], $Query, MYSQLI_STORE_RESULT);

			// IFTOPICSUCCESS
			if ( !$Query ) {
				if ( $Sitewide_Debug ) echo 'Invalid Query (Responses): '.mysqli_error($Database['Connection']);
				return false;

			// IFTOPICSUCCESS
			} else {

				// WHILERESPONSES
				while ( $Fetch = mysqli_fetch_assoc($Query) ) {

					// Parse the Post (strip_tags to remove html)
					$Post = strip_tags(Parsedown::instance()->parse(html_entity_decode($Fetch['Post'], ENT_QUOTES, 'UTF-8')));

					// Assemble the link
					// TODO
					// Link should go to right page and #id of the topic,
					// may not be the first. Could be done with Feed Pagination.
					$Link = $Sitewide_Root.$Canonical.'?topic='.$Fetch['Canonical'];

					// Echo the item
					echo '
		<item>
			<description>'.$Post.'</description>
			<link>'.$Link.'</link>
			<guid>'.$Link.'</guid>
			<pubDate>'.date('r', $Fetch['Created']).'</pubDate>
		</item>';

				} // WHILERESPONSES

			} // IFTOPICSUCCESS

		} // IFEXISTSRESPONSES

	// IFTOPIC
	} else {

		// IFEXISTSTOPICS
		if ( !$Database['Exists']['Topics'] ) return false;
		else {

			// Get the Category
			if (isset($_GET['category'])) $Category = Input_Prepare($_GET['category']);
			else $Category = false;

			// Send the right header for an RSS Feed
			header('Content-Type: application/rss+xml');

			// Send the beginning of the RSS Feed
			if ($Category) echo Feed_Header($Canonical.'?feed&category='.$Category);
			else echo Feed_Header($Canonical.'?feed');

			// Assemble the Query
			// TODO Feed Pagination
			$Query = 'SELECT `Slug`, `Title`, `Created` FROM `'.$Database['Prefix'].'Topics` WHERE';
			if ($Category) $Query .= ' `Category`=\''.$Category.'\' AND';
			if ($Member_Auth) $Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
			else $Query .= ' `Status`=\'Public\'';
			$Query .= ' ORDER BY `Created` DESC';

			// Execure the Query
			$Query = mysqli_query($Database['Connection'], $Query, MYSQLI_STORE_RESULT);

			// IFCATEGORYSUCCESS
			if ( !$Query ) {
				if ( $Sitewide_Debug ) echo 'Invalid Query (Topics): '.mysqli_error($Database['Connection']);
				return false;

			// IFCATEGORYSUCCESS
			} else {

				// WHILETOPICS
				while ( $Fetch = mysqli_fetch_assoc($Query) ) {

					// Fetch the Title
					$Title = $Fetch['Title'];

					// Assemble the Link
					$Link = $Sitewide_Root.$Canonical.'?topic='.$Fetch['Slug'];

					// Echo the Item
					echo '
		<item>
			<title>'.$Title.'</title>
			<link>'.$Link.'</link>
			<guid>'.$Link.'</guid>
			<pubDate>'.date('r', $Fetch['Created']).'</pubDate>
		</item>';

				} // WHILETOPICS

			} // IFCATEGORYSUCCESS

		} // IFEXISTSTOPICS

	} // IFTOPIC

	// End the RSS Feed
	echo '
	</channel>
</rss>';

	// Success
	return true;

}