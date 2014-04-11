<?php

// ### Forum Topic Slug Function ###
//
// Increments Topic_Slug
//
// Forum_Topic_Slug('slug');

function Forum_Topic_Slug($Topic_Slug, $Strict = true) {

	$Topic_Slug = strtolower($Topic_Slug);
	$Topic_Slug = htmlentities($Topic_Slug, ENT_QUOTES, 'UTF-8');
	$Topic_Slug = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $Topic_Slug);
	$Topic_Slug = html_entity_decode($Topic_Slug, ENT_QUOTES, 'UTF-8');
	$Topic_Slug = strip_tags($Topic_Slug);
	$Topic_Slug = stripslashes($Topic_Slug);
	$Topic_Slug = str_replace('\'', '', $Topic_Slug);
	$Topic_Slug = str_replace('"', '', $Topic_Slug);
	$Topic_Slug = preg_replace('/[^a-z0-9]+/', '-', $Topic_Slug);
	$Topic_Slug = strtr($Topic_Slug, 'àáâãäåòóôõöøèéêëðçìíîïùúûüñšž', 'aaaaaaooooooeeeeeciiiiuuuunsz');
	$Topic_Slug = preg_replace(array('/\s/', '/--+/', '/---+/'), '-', $Topic_Slug);
	$Topic_Slug = trim($Topic_Slug, '-');

	if (Forum_Topic_Check($Topic_Slug)) {
		$Topic_Slug_Parts = explode('-', $Topic_Slug);
		$Topic_Slug_Last = count($Topic_Slug_Parts) - 1;
		$Topic_Slug_Last = $Topic_Slug_Parts[$Topic_Slug_Last];
		if (is_numeric($Topic_Slug_Last)) {
			$Topic_Slug_Last_Length = count($Topic_Slug_Last);
			$Topic_Slug_End = $Topic_Slug_Last + 1;
			$Topic_Slug = substr($Topic_Slug, 0, -$Topic_Slug_Last_Length).$Topic_Slug_End;
			return Forum_Topic_Slug($Topic_Slug, false);
		} else return Forum_Topic_Slug($Topic_Slug.'-1', false);
	} else return $Topic_Slug;

}
