<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['libs'].'Parsedown.php';
include_once $Backend['functions'].'output.parse.php';
$Return['Name'] = 'Output Parse';
$Return['Status'] = 'Failure';

$String_In = '"<hello>" \' && © rock&roll `<world> &life;`';
$String_In = htmlspecialchars($String_In, ENT_QUOTES, 'UTF-8');
$String_Proper = '<p>&quot;&lt;hello&gt;&quot; &apos; &amp;&amp; © rock&amp;roll <code>&lt;world&gt; &amp;life;</code></p>';

$String_Out = Output_Parse($String_In);

if ( $String_Out == $String_Proper ) {

	$Return['Status'] = 'Success';

	$Return['Result']['Input'] = $String_In;
	$Return['Result']['Proper'] = $String_Proper;
	$Return['Result']['Output'] = $String_Out;

} else {
	array_push($Return['Errors'], 'String_Out does not match.');

}

echo API_Output($Return);