<?php

	$TextTitle = 'Simplet';
	$WebTitle = 'a simple, file-based, cms framework written in php';
	$Canonical = '';
	$PostType = 'Index';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = '';

	require_once '../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../header.php'; ?>

	</div>
	<div class="content spanpage clouds">
		<a href="https://github.com/eustasy/simplet">
			<img src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png" alt="Simplet on GitHub" class="tr-banner">
		</a>
		<div class="bubble">
			<h3>a simple, file-based, cms framework written in php</h3>
			<hr>
			<p class="italic faded textcenter">with members, forums, and blogging built in</p>
		</div>
		<div class="credit"><p>Background by <a href="http://bo0xvn.deviantart.com/">bo0xVn</a></p></div>
		<div class="clear"></div>
	</div>

	<div class="content">
		<div class="section group">
			<div class="col span_1_of_2">
				<h3>About</h3>
				<p>Simplet is a small, simple, file-based Content Management System designed for use on Linux/Nginx/PHP5-FPM stacks with MySQL-like database systems (we prefer <a href="http://mariadb.org">MariaDB</a>) that includes a basic membership system, blog and forum options plus a nice default responsive template for you to build on.</p>
				<p>It aims to extend the HTML5 Boilerplates by building one directly into a PHP Framework all of its own.</p>
			</div>
			<div class="col span_1_of_2">
				<h3>Pieces</h3>
				<h3><a href="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/blog/">Blog</a></h3>
				<h3><a href="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/forum/">Forum</a></h3>
				<h3><a href="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/account/">Members</a></h3>
			</div>
		</div>
	</div>

	<div class="content spanpage rocks">
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
					<blockquote>
						<p>Who are you? How did you get in my house?</p>
					</blockquote>
					<p class="italic faded textright">Stanford Algorithms Expert, <a href="http://xkcd.com/163/">Donald Knuth</a></p>
				</div>
			</div>
		</div>
		<div class="credit"><p>Another background by <a href="http://bo0xvn.deviantart.com/">bo0xVn</a></p></div>
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

<?php require '../footer.php'; } ?>
