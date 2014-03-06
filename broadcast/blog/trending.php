<?php

	$Title_HTML = 'Trending Blog Posts';
	$Title_Plain = 'Trending Blog Posts';

	$Description_HTML = 'Trending Blog Posts';
	$Description_Plain = 'Trending Blog Posts';

	$Keywords = 'trending blog posts';

	$Featured_Image = '';

	$Canonical = 'blog/trending';

	$Post_Type = 'Blog Index';
	$Post_Category = '';

	require_once '../../request.php';

if ($Request_Path_Entities == $Place['path'].$Canonical) {

	require '../../header.php';
	echo '<h2>Trending</h2>';

	$Trending = Trending(basename(__FILE__));
	foreach ($Trending as $Trending_Canonical => $Trending_Count) {
		if (substr($Trending_Canonical, -1) == '/') {
			require $Trending_Canonical.'index.php';
		} else {
			require $Trending_Canonical.'.php';
		}
		echo '<p><a href="'.$Sitewide_Root.$Trending_Canonical.'">'.$Title_HTML.'<span class="floatright">'.$Trending_Count.'</span></a></p>';
	}

	require '../../footer.php';

}
