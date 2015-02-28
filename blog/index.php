<?php

$Page['Title']['Plain'] = 'Blog';
$Page['Description']['Plain'] = 'Our blog.';
$Page['Keywords'] = 'blog posts';
$Page['Type'] = 'Blog Index';
$Canonical = '/blog/';

require_once __DIR__.'/../_simplet/request.php';
if ( $Request['Path'] === $Canonical ) {
	require $Templates['Header'];

	echo '
		<div class="breaker"></div>';

	// IFCATEGORY
	if (!empty($_GET['category'])) {
		$Category = Input_Prepare($_GET['category']);
	} else {
		$Category = false;
	}

	Blog(basename(__FILE__), $Category);

	echo '
		<div class="breaker"></div>
		<div class="section group widgets faded">
			<div class="col span_5_of_11 widget categories">
				<h3>Categories</h3>';

	// GETCATEGORIES
	$Categories = Blog_Categories(basename(__FILE__), $Category);

	// FORCATEGORIES
	foreach ($Categories as $Categories_Slug => $Categories_Count) {
		echo '
			<p><a href="?category='.$Categories_Slug.'">'.$Categories_Slug.'<span class="floatright">'.number_format($Categories_Count).'</span></a></p>';
	}
	echo '
		</div>
		<div class="col span_1_of_11"><br></div>
		<div class="col span_5_of_11 widget trending">
			<h3>Trending</h3>';

	$Trending = Views_Trending(basename(__FILE__));
	if ( count($Trending) ) {
		foreach ($Trending as $Trending_Canonical => $Trending_Count) {
			if (substr($Trending_Canonical, -1) == '/') {
				include $Backend['root'].$Trending_Canonical.'index.php';
			} else {
				include $Backend['root'].$Trending_Canonical.'.php';
			}
			if ( !isset($Page['Title']['HTML']) ) {
				$Page['Title']['HTML'] = $Page['Title']['Plain'];
			}
			echo '
				<p class="textcenter"><a href="'.$Sitewide['Root'].$Trending_Canonical.'">'.$Page['Title']['HTML'].' ('.$Trending_Count.')</a></p>';
			$Page['Title']['HTML'] = null;
		}
	} else {
		echo '
			<p class="textcenter">No trending posts available.</p>';
	}

	// Fin
	echo '
		</div>
	</div>
	<div class="breaker"></div>';

	require $Templates['Footer'];

}