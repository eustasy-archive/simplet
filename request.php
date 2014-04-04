<?php



////   Set the Default Timezone.

// Note: GMT is deprecated. Use UTC instead.
date_default_timezone_set('UTC');



////   Set the Inclusion Path

// Add Windows Support
if (!defined('PATH_SEPARATOR')) {
	if (strpos($_ENV['OS'], 'Win') !== false) define('PATH_SEPARATOR', ';');
	else define('PATH_SEPARATOR', ':');
}

// Set Include_Path
$Include_Path = get_include_path().PATH_SEPARATOR.__DIR__.PATH_SEPARATOR.__DIR__.DIRECTORY_SEPARATOR.'broadcast'.PATH_SEPARATOR.__DIR__.DIRECTORY_SEPARATOR.'public_html';
set_include_path($Include_Path);



//// Include the Configuration
include 'config.php';
// This is not require because it might fail if not yet installed.
// `once.connect.php` will handle failures, to some extent.



//
$Place = parse_url($Sitewide_Root);

$Request = parse_url($Place['scheme'].'://'.$Place['host'].$_SERVER['REQUEST_URI']);

if ($PHP_Strip && substr($Request['path'], -4, 4) == '.php') {
	header ('HTTP/1.1 301 Moved Permanently');
	header ('Location: '.$Sitewide_Root.$Canonical);
}

$Time = time();
$Cookie_Session = str_replace( '.', '_', $Place['host']).'_session';

$Post_Types = array('Page', 'Blog', 'Blog Index', 'Blog Category', 'Blog Post', 'Forum', 'Forum Index', 'Forum Category', 'Forum Topic');

require 'onces/once.connect.php';
require 'onces/once.auth.php';

require 'libs/Parsedown.php';

require 'functions/function.globrecursive.php';

require 'functions/function.stringgenerator.php';
require 'functions/function.passhash.php';

require 'functions/function.pagination.preservequerystrings.php';
require 'functions/function.pagination.pre.php';
require 'functions/function.pagination.links.php';

require 'functions/function.jsondo.php';

require 'functions/function.database.table.exists.php';

require 'functions/function.forum.categories.php';
require 'functions/function.forum.category.check.php';
require 'functions/function.forum.category.info.php';
require 'functions/function.forum.topics.php';

require 'functions/function.responses.php';
require 'functions/function.respond.php';

require 'functions/function.blog.php';
require 'functions/function.blog.categories.php';

require 'functions/function.viewcount.php';
require 'functions/function.trending.php';

require 'functions/function.runonce.create.php';
require 'functions/function.runonce.check.php';
require 'functions/function.runonce.delete.php';

// Count View
// Forums have Categories and Topics, and should be counted later.
if ($Post_Type !== 'Forum') ViewCount();
