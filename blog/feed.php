<?php

$Page['Title']['HTML'] = 'RSS Feed';
$Page['Title']['Plain'] = 'RSS Feed';
$Page['Description']['HTML'] = 'Our RSS Feed.';
$Page['Description']['Plain'] = 'Our RSS Feed.';
$Page['Keywords'] = 'rss feed';
$Page['Featured Image'] = '';
$Page['Type'] = 'Page';
$Page['Category'] = '';
$Canonical = '/blog/feed';

require_once __DIR__.'/../_simplet/request.php';
if ( $Request['Path'] === $Canonical ) {
	Feed_Files();
}