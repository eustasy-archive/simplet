<?

	require 'config.php';

	$Place = parse_url($Sitewide_Root);

	$Request = parse_url($Place['scheme'].'://'.$Place['host'].$_SERVER['REQUEST_URI']);

	if($PHP_Strip && substr($Request['path'], -4, 4) == '.php') {
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.$Sitewide_Root.$Canonical);
	}

	require 'once.connect.php';
	require 'once.auth.php';
	require 'libs/Parsedown.php';
	require 'function.responses.php';
	require 'function.respond.php';
