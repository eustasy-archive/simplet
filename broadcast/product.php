<?php

	$Title_HTML = 'Example Product';
	$Title_Plain = 'Example Product';

	$Description_HTML = 'An Example Product to demo Reviews.';
	$Description_Plain = 'An Example Product to demo Reviews.';

	$Keywords = 'product example rocks reviews demo';

	$Featured_Image = '';

	$Canonical = 'product';

	$Post_Type = 'Page';
	$Post_Category = '';

	require_once '../request.php';

if ($Request['path'] === $Place['path'].$Canonical) {

	require '../header.php';
	?>

		<h2>Example Product</h2>
		<div class="section group">
			<div class="col span_5_of_11">
				<img src="assets/images/Rocks.jpg" class="round">
			</div>
			<div class="col span_1_of_11"><br></div>
			<div class="col span_5_of_11">
				<p>Rocks. Lots and lots of rocks. Big ones. Various colors.</p>
				<p><a href="#" class="button blue">Buy <span class="floatright">$9,999.99</span></a></p>
			</div>
		</div>

	<?php
	Responses('Review');
	require '../footer.php';
}
