<?php

////	Set some Back-end locations

$Backend['root'] = __DIR__.'/../';
$Backend['libs'] = $Backend['root'].'_libs/';
$Backend['simplet'] = $Backend['root'].'_simplet/';
$Backend['onces'] = $Backend['simplet'].'onces/';
$Backend['functions'] = $Backend['simplet'].'functions/';

$Backend['templates'] = $Backend['root'].'_templates/';
$Templates['root'] = $Backend['templates'];
$Templates['Header'] = $Templates['root'].'header.php';
$Templates['Footer'] = $Templates['root'].'footer.php';

////	Require the Configuration
require_once $Backend['simplet'].'config.default.php';
if ( is_readable($Backend['simplet'].'config.custom.php') ) {
	require_once $Backend['simplet'].'config.custom.php';
}

// Handle Request and Redirect if necessary.
$Place = parse_url($Sitewide['Root']);
require_once $Backend['onces'].'request.php';
require_once $Backend['onces'].'stripphp.php';

// Set a Timezone and Connect
require_once $Backend['onces'].'timezone.php';
require_once $Backend['onces'].'connect.php';

// Handle User and Member Variables
require_once $Backend['onces'].'cookies.php';
require_once $Backend['onces'].'user.php';
require_once $Backend['onces'].'isauth.php';
require_once $Backend['onces'].'csrfprotect.php';

// Set some headers, mostly for security.
require_once $Backend['onces'].'headers.php';

// Handle Tracking
require_once $Backend['onces'].'trackme.php';
require_once $Backend['onces'].'posttypes.php';
require_once $Backend['onces'].'view.php';

// Load everything that hasn't been loaded.
// This is why we use require_once.
require_once $Backend['onces'].'functions.php';
require_once $Backend['onces'].'libs.php';