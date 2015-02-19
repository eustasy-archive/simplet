<?php

// Count View
// Forums have Categories and Topics, and should be counted later.
if (
	$User['Tracking'] &&
	$Page['Type'] == 'Post'
) {
	require_once $Backend['functions'].'view.count.php';
	View_Count();
}