<?php

	// Set a default timezone.
	date_default_timezone_set('UTC');
	// Note: GMT is deprecated. Use UTC instead.

	// ini_set('include_path', dirname(__FILE__));
	// set_include_path(dirname(__FILE__));
	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__).'/broadcast');

	// Include the Configuration
	// This is not require because it might fail if not yet installed.
	// `once.connect.php` will handle this, to some extent.
	include 'config.php';

	$Place = parse_url($Sitewide_Root);

	$Request = parse_url($Place['scheme'].'://'.$Place['host'].$_SERVER['REQUEST_URI']);

	if ($PHP_Strip && substr($Request['path'], -4, 4) == '.php') {
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.$Sitewide_Root.$Canonical);
	}

	$Time = time();

	$Post_Types = array('Page', 'Blog', 'Blog Index', 'Blog Category', 'Blog Post', 'Forum', 'Forum Index', 'Forum Category', 'Forum Topic');

	require 'once.connect.php';
	require 'once.auth.php';

	require 'libs/Parsedown.php';

	require 'function.stringgenerator.php';
	require 'function.passhash.php';
	require 'function.responses.php';
	require 'function.respond.php';
	require 'function.viewcount.php';
	require 'function.trending.php';
	require 'function.blog.php';
	require 'function.categories.php';

	// Count View
	// Forums have Categories and Topics, and should be counted later.
	if ($Post_Type !== 'Forum') ViewCount();
