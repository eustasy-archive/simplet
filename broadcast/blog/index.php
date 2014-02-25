<?php

	$TextTitle = 'Blog';
	$WebTitle = 'Blog';
	$Canonical = 'blog/';
	$PostType = 'Blog';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'blog';

	require_once '../../request.php';

if ($Request_Path_Entities == $Place['path'].$Canonical) {

	require '../../header.php';

		echo '
		<div class="section group posts">';

		// Set Looper to 0
		$Looper = 0;

		// List all the files
		$Items = glob('*.php', GLOB_NOSORT);

		// Order them by time
		array_multisort(array_map('filemtime', $Items), SORT_NUMERIC, SORT_DESC, $Items);

		// FOREACH: For each Item
		foreach ($Items as $Item) {

			// IFNOTTHIS: So long as it isn't this file
			if ($Item != 'index.php') {

				// Require it
				require $Item;

				// IFPOST If it is a post (and hence has a time)
				if ($PostType == 'Post') {

					// Make the link
					$PostLink = $Sitewide_Root.$Canonical;

					// Echo out the Item
					echo '
			<div class="col span_5_of_12">
				<h2><a href="'.$PostLink.'">' . $TextTitle . '</a></h2>
				<p class="textright faded"><small>' . date ('d/m/Y', filemtime($Item)) .'</small></p>
				<p>' . $Description . '</p>
			</div>';

					//
					$Looper += 1;
					if (is_int($Looper/2)) {
						echo '
		</div>
		<div class="breaker"></div>
		<div class="section group">';
					} else {
						echo '
			<div class="col span_2_of_12"><br></div>';
					}


				} // IFPOST

			} // IFNOTTHIS

		} // FOREACH

		// Fin
		echo '
		</div>
		<div class="breaker"></div>';

	require '../../footer.php';

}
