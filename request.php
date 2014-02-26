<?

	require 'config.php';

	$Place = parse_url($Sitewide_Root);

	$Request = parse_url($Place['scheme'].'://'.$Place['host'].$_SERVER['REQUEST_URI']);

	$Request_Path_Entities = htmlentities($Request['path'], ENT_QUOTES, 'UTF-8');

	if($PHP_Strip && substr($Request['path'], -4, 4) == '.php') {
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.$Sitewide_Root.$Canonical);
	}

	$Time = time();

	require 'once.connect.php';
	require 'once.auth.php';

	require 'libs/Parsedown.php';

	require 'function.stringgenerator.php';
	require 'function.passhash.php';
	require 'function.responses.php';
	require 'function.respond.php';
	require 'function.viewcount.php';

	if ($PostType != 'Index' && $PostType != 'Forum' && $PostType != 'Blog' && $PostType != 'Sitemap' && $PostType != 'RSS' && $PostType != 'Store') ViewCount();
