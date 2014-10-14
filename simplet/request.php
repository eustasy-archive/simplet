<?php



////	Set the Default Timezone.

// Note: GMT is deprecated. Use UTC instead.
date_default_timezone_set('UTC');


////	Set the Inclusion Path

// Add Windows Support
if (!defined('PATH_SEPARATOR')) {
	if (strpos($_ENV['OS'], 'Win') !== false) define('PATH_SEPARATOR', ';');
	else define('PATH_SEPARATOR', ':');
}



// Set Include_Path
$Include_Path = __DIR__.PATH_SEPARATOR.__DIR__.DIRECTORY_SEPARATOR.'simplet'.PATH_SEPARATOR.__DIR__.DIRECTORY_SEPARATOR.'broadcast'.PATH_SEPARATOR.__DIR__.DIRECTORY_SEPARATOR.'public_html'.PATH_SEPARATOR.get_include_path();
$Broadcast = __DIR__.'/../broadcast/';



////	Include the Configuration
include 'config.php';
// This is not require because it might fail if not yet installed.
// `once.connect.php` will handle failures, to some extent.



////	Require Things
require 'onces/connect.php';

$Place = parse_url($Sitewide_Root);

$Request = parse_url($Place['scheme'].'://'.$Place['host'].$_SERVER['REQUEST_URI']);

if ($PHP_Strip && substr($Request['path'], -4, 4) == '.php') {
	header ('HTTP/1.1 301 Moved Permanently');
	header ('Location: '.$Sitewide_Root.$Canonical);
}

$Time = time();
$Time_15mins = $Time+900;
$Time_1hour = $Time+3600;
$Cookie_Session = str_replace( '.', '_', $Place['host']).'_session';

$Post_Types = array('Page', 'Blog', 'Blog Index', 'Blog Category', 'Blog Post', 'Forum', 'Forum Index', 'Forum Category', 'Forum Topic');

require 'functions/input.prepare.php';
require 'onces/isauth.php';

include __DIR__.'/../libs/Parsedown.php';

require 'functions/globrecursive.php';

require 'functions/generator.string.php';
require 'functions/pass.hash.php';

require 'functions/pagination.preservequerystrings.php';
require 'functions/pagination.pre.php';
require 'functions/pagination.links.php';

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

require 'functions/api.output.php';
require 'functions/api.output.xml.php';

require 'functions/views.count.php';
require 'functions/views.trending.php';

require 'functions/runonce.create.php';
require 'functions/runonce.check.php';
require 'functions/runonce.used.php';
require 'functions/runonce.delete.php';

require 'functions/time.readable.difference.php';

// Count View
// Forums have Categories and Topics, and should be counted later.
if ($Post_Type !== 'Forum') Views_Count();