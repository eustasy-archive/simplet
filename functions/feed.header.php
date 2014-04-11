<?php

// ### Feed Header Function ###
//
// Echos the Header for RSS Feeds
//
// Feed_Header();

function Feed_Header($URL) {

	// Set some Globals.
	global $Sitewide_Root, $Sitewide_Title, $Sitewide_Tagline;

	// Send the right header for an RSS Feed
	header('Content-Type: application/rss+xml');

	// Set the doctype and some basic information
	echo '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<atom:link href="'.$Sitewide_Root.$URL.'" rel="self" type="application/rss+xml" />
		<title>'.$Sitewide_Title.'</title>
		<description>'. $Sitewide_Tagline.'</description>
		<link>'. $Sitewide_Root.'</link>
		<language>en</language>
		<generator>Simplet</generator>';

}