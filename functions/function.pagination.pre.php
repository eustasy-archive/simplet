<?php

function Pagination_Pre($Count, $Show = 10, $Page = 1) {

	$Pagination = array();

	$Pagination['Page'] = $Page;
	$Pagination['Show'] = $Show;

	// Catch Page and Show
	if (isset($_GET['page'])) $Pagination['Page'] = intval($_GET['page']);
	if (isset($_GET['show'])) $Pagination['Show'] = intval($_GET['show']);

	// Stop Number being ridiculously large.
	if ($Pagination['Show'] > 100) $Pagination['Show'] = 100;
	if ($Pagination['Show'] < 1) $Pagination['Show'] = 10;
	if ($Pagination['Page'] < 1) $Pagination['Page'] = 1;

	// Stop Page being further than possible
	// (Show the last if it's over, first if negative)
	$Pagination['Page Max'] = ceil($Count/$Pagination['Show']);

	// Honor pagination
	if ($Pagination['Page'] !== 1) {
		if ($Pagination['Page'] > $Pagination['Page Max']) {
			if ($Pagination['Page Max'] < 1) {
				$Pagination['Page'] = 1;
			} else {
				$Pagination['Page'] = $Pagination['Page Max'];
			}
		}
		$Pagination['Start'] = ($Pagination['Page'] - 1) * $Pagination['Show'];
	} else $Pagination['Start'] = 0;

	return $Pagination;

}
