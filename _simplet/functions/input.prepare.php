<?php

////	Input Prepare Function
//
// Prepares a thing for MySQL use.
//
// Encoded characters:
// " becomes &quot;
// & becomes &amp;
// ' becomes &#039;
// \ becomes \\
// < becomes &lt;
// > becomes &gt;

function Input_Prepare($Thing, $Like = false) {

	global $Database;

	$Thing = htmlspecialchars($Thing, ENT_QUOTES, 'UTF-8');
	$Thing = mysqli_real_escape_string($Database['Connection'], $Thing);

	if ( $Like ) {
		$Thing = addcslashes($Thing, '%_');
	}

	return $Thing;

}