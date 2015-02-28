<?php

// Count View
// Forums have Categories and Topics, and should be counted later.
if (
	$User['Tracking'] &&
	in_array($Page['Type'], $Post_Types) &&
	$Page['Type'] != 'NOTRACK'
) {
	require_once $Backend['functions'].'view.count.php';
	View_Count();
}