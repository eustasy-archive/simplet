<?php

	// TODO Check Topic_ID
	// if ! error
	// else

	$TextTitle = 'Topic'; // TODO Add Topic Title
	$WebTitle = 'Topic'; // TODO Add Topic Title
	$Canonical = 'forum/topic';
	$PostType = 'Topic';
	$FeaturedImage = '';
	$Description = ''; // TODO Add Topic Description
	$Keywords = 'topic forum'; // TODO Add Topic Keywords

	require_once '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../../header.php'; ?>

		<h2>Start an Automated Deployment and Management Service for common Web Software</h2><!-- TODO Add Topic Title -->
		<p class="red">THIS IS A PREVIEW PAGE. FORUM NOT ACTIVE.</p>
		<p>Leveraging the Digital Ocean API (more server providers could be added later), users could pay to have our automated system deploy on their server, or buy servers from us (as a reseller). We could auto-deploy WordPress, Ghost, a MariaDB Stack with Nginx and PHPMyAdmin already set up. The possibilities are endless!</p><!-- TODO Add Topic Description -->
		<!-- TODO List Topic Posts -->
		<div class="section group darkrow">
			<div class="col span_2_of_12 textcenter"><p>Lewis Goddard</p></div>
			<div class="col span_10_of_12  faded"><p>22 Oct, 2013 22:15</p></div>
		</div>
		<div class="section group reply">
			<div class="col span_2_of_12"><img class="avatar" src="http://lewisgoddard.eustasy.org/images/faces/circular-blue-small-cropped-compressed.png"></div>
			<div class="col span_10_of_12">
				<p>Lorem ipsum dolor sit amet, <a href="#">test link</a> adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p>
			</div>
		</div>
		<div class="section group darkrow">
			<div class="col span_2_of_12 textcenter"><p>Lewis Goddard</p></div>
			<div class="col span_10_of_12 faded"><p>22 Oct, 2013 22:15</p></div>
		</div>
		<div class="section group reply">
			<div class="col span_2_of_12"><img class="avatar" src="http://lewisgoddard.eustasy.org/images/faces/circular-red-small-compressed.png"></div>
			<div class="col span_10_of_12">
				<p>Lorem ipsum dolor sit amet, emphasis consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p>
			</div>
		</div>

<?php require '../../footer.php'; } ?>
