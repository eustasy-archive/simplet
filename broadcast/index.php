<?php

	$Title_HTML = 'Simplet';
	$Title_Plain = 'Simplet';

	$Description_HTML = 'a simple, modular, cms framework written in php';
	$Description_Plain = 'a simple, modular, cms framework written in php';

	$Keywords = 'blog posts';

	$Featured_Image = '';

	$Canonical = '';

	$Post_Type = 'Page';
	$Post_Category = '';

	require_once __DIR__.'/../simplet/request.php';

if ($Request['path'] === $Place['path'].$Canonical) {
	require '../header.php';
	?>

	<div class="content spanpage wild">
		<a href="https://github.com/eustasy/simplet">
			<img src="assets/images/forkme.png" alt="Simplet on GitHub" class="tr-banner">
		</a>
		<div class="bubble">
			<h3>a simple, modular, community framework written in php</h3>
			<hr>
			<p class="italic faded textcenter">with members, comments, reviews and forums built in</p>
		</div>
		<!--<div class="credit"><p>Background by <a href="http://creditlink.com/">Person</a></p></div>-->
		<div class="clear"></div>
	</div>

	<div class="content">
		<div class="section group">
			<div class="col span_1_of_2">
				<h3>About</h3>
				<p>Simplet is a small, simple, system designed for use on Linux/Nginx/PHP5-FPM stacks with MySQL-like database systems (we prefer <a href="http://mariadb.org">MariaDB</a>) that includes a basic membership system, blog and forum options plus a nice default responsive template for you to drop in behind a previously static site to add in membership capabilities.</p>
				<p>It aims to extend the HTML5 Boilerplates by building one directly into a PHP Framework all of its own.</p>
			</div>
			<div class="col span_1_of_2">
				<h3>Pieces</h3>
				<h3><a href="<?php echo $Sitewide_Root; ?>/blog/">Blog</a></h3>
				<h3><a href="<?php echo $Sitewide_Root; ?>/forum/">Forum</a></h3>
				<h3><a href="<?php echo $Sitewide_Root; ?>/account/">Members</a></h3>
			</div>
		</div>
	</div>

	<div class="content spanpage sunset">
		<div class="section group">
			<div class="col span_5_of_11">
				<div class="bubble equalize">
					<h3>Responsive</h3>
					<p>Try resizing the page. Did you do it? See how cool these columns are, how they just drop down into place. That makes this website work on any screen down to 320px wide. That's about the size of the smallest phone screen with internet.</p>
				</div>
			</div>
			<div class="col span_1_of_11"><br></div>
			<div class="col span_5_of_11">
				<div class="bubble equalize">
					<h3>Responses</h3>
					<blockquote class="tiny">
						<p>Who are you? How did you get in my house?</p>
					</blockquote>
					<p class="italic faded textright">Stanford Algorithms Expert, <a href="http://xkcd.com/163/">Donald Knuth</a></p>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="content">
		<div class="section group">
			<div class="col span_1_of_2">
				<h3></h3>
				<p></p>
			</div>
			<div class="col span_1_of_2">
				<h3>Download</h3>
				<p>
			</div>
		</div>

	<?php
	require '../footer.php';
}