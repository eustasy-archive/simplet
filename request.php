<?

	require 'config.php';

	$Request = parse_url($Sitewide_Root.$_SERVER['REQUEST_URI']);

	// This is a per-page 301 to strip trailing php extensions
	if (substr($Request['path'], -4, 4) == '.php') {
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.$Request['scheme']['host'].$Canonical);
	}

	require 'connect.php';

	require 'is-auth.php';
