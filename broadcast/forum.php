<?php

	$TextTitle = 'Forum';
	$WebTitle = 'Forum';
	$Canonical = 'forum';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'forum';

	require '../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../header.php'; ?>

	<div class="content">

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

		<br><br><br>
		<h2>Start an Automated Deployment and Management Service for common Web Software</h2>
		<p>Leveraging the Digital Ocean API (more server providers could be added later), users could pay to have our automated system deploy on their server, or buy servers from us (as a reseller). We could auto-deploy WordPress, Ghost, a MariaDB Stack with Nginx and PHPMyAdmin already set up. The possibilities are endless!</p>
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

	</div>

<?php require '../footer.php'; } ?>
