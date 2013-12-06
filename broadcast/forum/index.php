<?php

	$TextTitle = 'Forum';
	$WebTitle = 'Forum';
	$Canonical = 'forum/';
	$PostType = 'Forum';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'forum';

	require_once '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../../header.php'; ?>

		<h2>Forum</h2>
		<p class="red">THIS IS A PREVIEW PAGE. FORUM NOT ACTIVE.</p>

		<div class="section group darkrow">
			<div class="col span_1_of_12"><br></div>
			<div class="col span_7_of_12"><p>Topic</p></div>
			<div class="col span_2_of_12 textcenter faded"><p>Replies</p></div>
			<div class="col span_2_of_12 textcenter faded"><p>Posted</p></div>
		</div>
		<a href="#" class="section group topic">
			<div class="col span_1_of_12"><li class="icon read"></li></div>
			<div class="col span_7_of_12"><p class="title">Start an Automated Deployment and Management Service for common Web Software</p></div>
			<div class="col span_2_of_12 textcenter"><p><span>14<span></p></div>
			<div class="col span_2_of_12 textcenter"><p><span>29 Oct, 2013 04:53</span></p></div>
			<div class="clear"></div>
			<div class="col span_1_of_12"><br></div>
			<div class="col span_11_of_12"><p>Leveraging the Digital Ocean API (more server providers could be added later), users could pay to have our automated system deploy on their server, or buy servers from us (as a reseller).</p></div>
		</a>
		<a href="#" class="section group topic">
			<div class="col span_1_of_12"><li class="icon unread"></li></div>
			<div class="col span_7_of_12"><p class="title">Start an Automated Deployment and Management Service</p></div>
			<div class="col span_2_of_12 textcenter"><p><span>12</span></p></div>
			<div class="col span_2_of_12 textcenter"><p><span>22 Oct, 2013 22:15</span></p></div>
			<div class="clear"></div>
			<div class="col span_1_of_12"><br></div>
			<div class="col span_11_of_12"><p>Leveraging the Digital Ocean API (more server providers could be added later), users could pay to have our automated system deploy on their server, or buy servers from us (as a reseller).</p></div>
		</a>

<?php require '../../footer.php'; } ?>
