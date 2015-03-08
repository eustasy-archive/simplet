<?php

function Member_Session_New() {
	global $Backend, $Cookie, $Database, $Login_Mail, $Member, $Redirect, $Request, $Sitewide, $Time, $User;
	$Member['Cookie'] = Generator_String();
	setcookie($Cookie['Session'], $Member['Cookie'], $Time['1month'], '/', $Request['Host'], $Request['Secure'], $Request['HTTPOnly']);
	$Session_New = 'INSERT INTO `'.$Database['Prefix'].'Sessions` (`Member_ID`, `Mail`, `Cookie`, `IP`, `Active`, `Created`, `Modified`) VALUES (\''.$Member['ID'].'\', \''.$Login_Mail.'\', \''.$Member['Cookie'].'\', \''.$User['IP'].'\', \'1\', \''.$Time['Now'].'\', \''.$Time['Now'].'\')';
	$Session_New = mysqli_query($Database['Connection'], $Session_New, MYSQLI_STORE_RESULT);
	if ( !$Session_New ) {
		if ( $Backend['Debug'] ) {
			echo 'Invalid Query (Session_New): '.mysqli_error($Database['Connection']);
		}
		return 'Login Error: Could not create session.';
	} else {
		if ( isset($Redirect) ) {
			header('Location: '.$Sitewide['Root'].urldecode($Redirect), true, 302);
		} else {
			header('Location: '.$Sitewide['Account'], true, 302);
		}
		return true;
	}
}