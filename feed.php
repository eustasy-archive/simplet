<?php

$Page['Title']['Plain'] = 'RSS Feed';
$Page['Description']['Plain'] = 'Our RSS Feed.';
$Page['Keywords'] = 'rss feed';
$Page['Type'] = 'Feed';
$Canonical = '/feed';

require_once __DIR__.'/_simplet/request.php';
if ( $Request['Path'] === $Canonical ) {
	Feed_Files();
}