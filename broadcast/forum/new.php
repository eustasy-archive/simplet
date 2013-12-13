<?php

function sluggify($url) {

	# Prep string with some basic normalization
	$url = strtolower($url);

	$url = htmlentities($url, ENT_QUOTES, 'UTF-8');
	$url = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $url);
	$url = html_entity_decode($url, ENT_QUOTES, 'UTF-8');

	$url = strip_tags($url);
	$url = stripslashes($url);

	# Remove quotes (can't, etc.)
	$url = str_replace('\'', '', $url);

	# Replace non-alpha numeric with hyphens
	$url = preg_replace('/[^a-z0-9]+/', '-', $url);

	$url = strtr($url, 'àáâãäåòóôõöøèéêëðçìíîïùúûüñšž', 'aaaaaaooooooeeeeeciiiiuuuunsz');
	$url = preg_replace(array('/\s/', '/--+/', '/---+/'), '-', $url);

	$url = trim($url, '-');

	return $url;

}

echo sluggify('hello/worlde');
echo '<br>';
echo sluggify('hello-<span class="hello" style="color:#666">worlde</span>/@!"£$%&(_-</h2><?+----');
echo '<br>';
echo sluggify('Ubuntu 12.04 Precise Pangolin');