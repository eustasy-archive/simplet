<?php

////	Output Parse Function
//
//

function Output_Parse($String, $AllowHTML = false) {

	global $Sitewide_AllowHTML;

	$Prepared = array(
		'&amp;amp;',
		'&amp;quot;',
		'&amp;#039;',
		'&amp;apos;',
		'&amp;lt;',
		'&amp;gt;',
		'&#039;'
	);

	$PrePrepared = array(
		'&amp;',
		'&quot;',
		'&apos;',
		'&apos;',
		'&lt;',
		'&gt;',
		'&apos;'
	);

	if ( $Sitewide_AllowHTML || $AllowHTML ) $String = htmlspecialchars_decode($String, ENT_QUOTES, 'UTF-8');
	$String = Parsedown::instance()->parse($String);
	$String = str_replace($Prepared, $PrePrepared, $String);

	return $String;

}