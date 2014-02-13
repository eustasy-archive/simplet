<?php

	$TextTitle = 'Example Product';
	$WebTitle = 'Example Product';
	$Canonical = 'product';
	$PostType = 'Product';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'example product';

	require_once '../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

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
	require '../responses.php';
	Responses('Review');
	require '../footer.php';
}
