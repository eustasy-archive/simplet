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

require 'onces/connect.php';
require 'onces/isauth.php';

require 'libs/Parsedown.php';

require 'functions/globrecursive.php';

require 'functions/stringgenerator.php';
require 'functions/passhash.php';

require 'functions/pagination.preservequerystrings.php';
require 'functions/pagination.pre.php';
require 'functions/pagination.links.php';

require 'functions/jsondo.php';

require 'functions/database.table.exists.php';

require 'functions/feed.header.php';
require 'functions/feed.files.php';
require 'functions/feed.forum.php';

require 'functions/forum.categories.php';
require 'functions/forum.category.check.php';
require 'functions/forum.category.info.php';
require 'functions/forum.category.count.php';
require 'functions/forum.category.increment.php';
require 'functions/forum.category.modified.php';

require 'functions/forum.topics.php';
require 'functions/forum.topic.slug.php';
require 'functions/forum.topic.check.php';
require 'functions/forum.topic.info.php';
require 'functions/forum.topic.count.php';
require 'functions/forum.topic.increment.php';
require 'functions/forum.topic.modified.php';

require 'functions/responses.php';
require 'functions/respond.php';

require 'functions/blog.php';
require 'functions/blog.categories.php';

require 'functions/viewcount.php';
require 'functions/trending.php';

require 'functions/runonce.create.php';
require 'functions/runonce.check.php';
require 'functions/runonce.delete.php';

// Count View
// Forums have Categories and Topics, and should be counted later.
if ($Post_Type !== 'Forum') ViewCount();
