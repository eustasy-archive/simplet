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

	<div class="content spanpage" style="background-image: url('assets/images/Blueprint.jpg');">
		<a href="https://github.com/eustasy/simplet/tree/security">
			<img src="assets/images/forkme.png" alt="Simplet Security on GitHub" class="tr-banner">
		</a>
		<div class="bubble">
			<h3><?php echo $Sitewide_Tagline; ?></h3>
			<hr>
			<p class="italic faded textcenter">Help test a system with thousands of users by trying to break into it.</p>
		</div>
		<!--<div class="credit"><p>Background by <a href="http://creditlink.com/">Person</a></p></div>-->
		<div class="clear"></div>
	</div>

	<div class="content">
		<div class="section group">
			<div class="col span_5_of_11">
				<h3 class="textleft">Simplet</h3>
				<p>Simplet is a simple, modular, community framework and member management system written in PHP, and is designed for use on designed for use on Linux/Nginx/PHP5-FPM stacks with MySQL-like database systems (we prefer <a href="http://mariadb.org">MariaDB</a>). While used prominently as a backend for <a href="http://eustasy.org">eustasy</a> projects, it has escaped serious security auditing thus far.</p>
				<h4>That changes now.</h4>
			</div>
			<div class="col span_1_of_11"><br></div>
			<div class="col span_5_of_11">
				<h3>Get to Work</h3>
				<p>If you want to help try to find security flaws in Simplet, you'll need to go through some basic steps, and maybe set up your own environment. This short guide gives you a few good starting places. You'll probably want a LEMP stack too. You can locally host your own virtual machine, or use a service like <a href="https://www.digitalocean.com/?refcode=eca8f5e3972c">Digital Ocean</a>. ($5/month servers!)</p>
				<p class="textright"><a href="security/get-started">Get Started &raquo;</a></p>
			</div>
		</div>
	</div>

	<div class="content spanpage" style="background-image: url('assets/images/Mountains_by_JamesPickles.jpg');">
		<h1 class="textcenter color-white">Things you can hack</h1>
		<div class="section group">
			<div class="col span_5_of_11">
				<div class="bubble equalize">
					<h2>Protected</h2>
					<h3><a href="<?php echo $Sitewide_Root; ?>administration">Administration</a></h3>
					<h3><a href="<?php echo $Sitewide_Root; ?>protected-page">Protected (Members Only)</a></h3>
					<h3><a href="<?php echo $Sitewide_Root; ?>forum">Forum with Private Category</a></h3>
				</div>
			</div>
			<div class="col span_1_of_11"><br></div>
			<div class="col span_5_of_11">
				<div class="bubble equalize">
					<h2>Responses</h2>
					<h3><a href="<?php echo $Sitewide_Root; ?>product">Product with Ratings</a></h3>
					<h3><a href="<?php echo $Sitewide_Root; ?>page-with-comments">Page with Comments</a></h3>
					<h3><a href="<?php echo $Sitewide_Root; ?>forum">Forum with Topics & Posts</a></h3>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="content">
		<div class="section group">
			<div class="col span_5_of_11">
				<blockquote class="twitter-tweet" lang="en">
					<img src="https://pbs.twimg.com/media/Bzg6G4-CMAAjjdF.png:large">
					<p>It&#39;s National Cyber Security Awareness Month. Stay tuned for daily security tips from Google Apps. <a href="https://twitter.com/hashtag/ncsam?src=hash">#ncsam</a> <a href="http://t.co/Erly71RTDX">pic.twitter.com/Erly71RTDX</a></p>&mdash; Google for Work (@GoogleforWork) <a href="https://twitter.com/GoogleforWork/status/520229647548944384">October 9, 2014</a>
				</blockquote>
				<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
			</div>
			<div class="col span_1_of_11"><br></div>
			<div class="col span_5_of_11">
				<h3><a href="security/levels">Secuirty Levels</a></h3>
				<p>Security Levels define how much damage an attack seems to cause, ranging from Level 5 (previously fixed issues), to Level 1 (changing passwords or modifying the site). A higher security level means less damage, with bounties only going to the lowest levels, 1 and 2.</p>
				<p class="textright"><a href="security/levels">Read More &raquo;</a></p>
				<p>Responsible Disclosure is all about fixing the secuirty issues in software in a way that does not jeopardise the security of current installations.</p>
				<p class="textright"><a href="security/responsible-disclosure">Read More &raquo;</a></p>
			</div>
		</div>

	<?php
	require '../footer.php';
}