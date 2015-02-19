<?php

if (
	$Backend['Strip PHP from URLs'] &&
	substr($Request['Path'], -4, 4) == '.php'
) {
	header ('HTTP/1.1 301 Moved Permanently');
	header ('Location: '.$Sitewide_Root.$Canonical);
}