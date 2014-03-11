<?php

	$Title_HTML = 'Blog';
	$Title_Plain = 'Blog';

	$Description_HTML = 'Our blog.';
	$Description_Plain = 'Our blog.';

	$Keywords = 'blog posts';

	$Featured_Image = '';

	$Canonical = 'blog/';

	$Post_Type = 'Blog Index';
	$Post_Category = '';

	require_once '../../request.php';

if (substr($Request['path'], 0, strlen($Place['path'].$Canonical)) === $Place['path'].$Canonical) {

	require '../../header.php';

		// IFCATEGORY
		if (isset($_GET['category'])) {
			$Category = htmlentities($_GET['category'], ENT_QUOTES, 'UTF-8');
		} else {
			$Category = false;
		}

		Blog(basename(__FILE__), $Category);

		echo '
		<div class="section group widgets faded">
			<div class="col span_5_of_11 widget categories">
				<h3>Categories</h3>';

				// GETCATEGORIES
				$Categories = Categories(basename(__FILE__), $Category);

				// FORCATEGORIES
				foreach ($Categories as $Categories_Slug => $Categories_Count) echo '
				<p><a href="?category='.$Categories_Slug.'">'.$Categories_Slug.'<span class="floatright">'.number_format($Categories_Count).'</span></a></p>';
				echo '
			</div>
			<div class="col span_1_of_11"><br></div>
			<div class="col span_5_of_11 widget trending">
				<h3>Trending</h3>';

				$Trending = Trending(basename(__FILE__));
				foreach ($Trending as $Trending_Canonical => $Trending_Count) {
					if (substr($Trending_Canonical, -1) == '/') {
						require $Trending_Canonical.'index.php';
					} else {
						require $Trending_Canonical.'.php';
					}
					echo '
				<p><a href="'.$Sitewide_Root.$Trending_Canonical.'">'.$Title_HTML.'<span class="floatright">'.number_format($Trending_Count).'</span></a></p>';
				}

		// Fin
		echo '
			</div>
		</div>
		<div class="breaker"></div>';

	require '../../footer.php';

}
