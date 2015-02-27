<?php

$Page['Title']['HTML'] = 'Example Product';
$Page['Title']['Plain'] = 'Example Product';
$Page['Description']['HTML'] = 'An Example Product to demo Reviews.';
$Page['Description']['Plain'] = 'An Example Product to demo Reviews.';
$Page['Keywords'] = 'product example rocks reviews demo';
$Page['Featured Image'] = '';
$Page['Type'] = 'Page';
$Page['Category'] = '';
$Canonical = '/product';

require_once __DIR__.'/_simplet/request.php';
if ( $Request['Path'] === $Canonical ) {
	require $Templates['Header'];
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
	require $Templates['Footer'];
}