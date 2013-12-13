<?php

function post($post) {

	$post = htmlentities($post, ENT_QUOTES, 'UTF-8');

	$post = trim($post);

	return $post;

}

echo post('hello/worlde');
echo '<br>';
echo post('hello-<span class="hello" style="color:#666">worlde</span>/@!"£$%&(_-</h2><?+----');
echo '<br>';
echo post('Ubuntu <span>12.04</span> < hello owl > Precise Pangolin');
