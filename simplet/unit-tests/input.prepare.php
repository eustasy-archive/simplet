<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once __DIR__.'/../functions/input.prepare.php';
$Return['Name'] = 'Input Prepare';
$Return['Status'] = 'Failure';

$String_In = 'abcdefghijklmnopqrstuvwxyz0123456789`!"£$%^&*()_+-={}[]:@~;\'|\#<>?,./';
$String_Proper = 'abcdefghijklmnopqrstuvwxyz0123456789`!&quot;&pound;$%^&amp;*()_+-={}[]:@~;&#039;|\\\#&lt;&gt;?,./';
$String_Out = Input_Prepare($String_In);
// string(96) "abcdefghijklmnopqrstuvwxyz0123456789`!&quot;&pound;$%^&amp;*()_+-={}[]:@~;&#039;|\\#&lt;&gt;?,./"

$String_Like_In = 'abcdefghijklmnopqrstuvwxyz0123456789`!"£$%^&*()_+-={}[]:@~;\'|\#<>?,./';
$String_Like_Proper = 'abcdefghijklmnopqrstuvwxyz0123456789`!&quot;&pound;$\%^&amp;*()\_+-={}[]:@~;&#039;|\\\#&lt;&gt;?,./';
$String_Like_Out = Input_Prepare($String_Like_In, true);
// string(98) "abcdefghijklmnopqrstuvwxyz0123456789`!&quot;&pound;$\%^&amp;*()\_+-={}[]:@~;&#039;|\\#&lt;&gt;?,./"

// WARN: You must double-escape the backslashes for this to work.
// \\ becomes \\\

if (
	$String_Out == $String_Proper &&
	$String_Like_Out == $String_Like_Proper
) {

	$Return['Status'] = 'Success';

	$Return['Result']['Standard'] = array();
	$Return['Result']['Standard']['Input'] = $String_In;
	$Return['Result']['Standard']['Output'] = $String_Out;

	$Return['Result']['Like'] = array();
	$Return['Result']['Like']['Input'] = $String_Like_In;
	$Return['Result']['Like']['Output'] = $String_Like_Out;

} else {
	if ( $String_Out != $String_Proper ) array_push($Return['Errors'], 'String_Out does not match.');
	if ( $String_Like_Out != $String_Like_Proper ) array_push($Return['Errors'], 'String_Like_Out does not match.');

}

echo API_Output($Return);