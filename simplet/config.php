<?php

	// Site Configuration
	$Sitewide_Debug = false;

	// This is the Title of your Site
	$Sitewide_Title = 'Simplet Security';

	// And the tagline
	$Sitewide_Tagline = 'Security testing on Simplet for NCSAM.';

	// This is the Base URL of your Site
	// Do include a trailing slash
	// Do include a http:// or https://
	$Sitewide_Root = 'https://security.simplet.eustasy.org/';
	// $Sitewide_Root = 'http://example.com/';
	// $Sitewide_Root = 'https://something.000space.com/';

	// Allow or disallow Signups
	$Sitewide_Signups = true;

	// Enable or Disable Browning Mail
	// Requires Setup in libs
	$Browning = true;

	// Strip .php Extensions
	// IMPORTANT: Requires additional server-side setup.
	$PHP_Strip = true;

	// Values:
	// true = Full checking
	// 'Partial' = First two segment checking
	// false = No checking
	$IP_Checking = 'Partial';


	// Forum configuration

	// Topics Inherit Category Status
	// Eg. Public Categories have automatically Public Topics,
	// Private Categories have Private Topics.
	$Forum_Topic_Inherit = true;

	// Default Topic Status
	// Set a custom Status for new Topics
	// Eg. 'Pending' to hold all topics for moderation.
	// Not used if $Forum_Reply_Inherit is set to true.
	$Forum_Topic_Default = 'Public';

	// Replies Inherit Topic Status
	// Eg. Public Topics have automatically Public Replies,
	// Private Topics have Private Replies.
	$Forum_Reply_Inherit = true;

	// Default Reply Status
	// Set a custom Status for Replies
	// Eg. 'Pending' to hold all replies for moderation.
	// Not used if $Forum_Reply_Inherit is set to true.
	$Forum_Reply_Default = 'Pending';
	$Response_Status_Reviews = 'Pending';
	$Response_Status_Comments = 'Pending';

	$Sitewide_Comments_Helpful = true;
	$Sitewide_Posts_Helpful = true;



	// Choose a hash method.
	$Sitewide_Security_HashMethod = 'sha512';
	// Note: Could also use sha1, sha512 etc, etc
	// Note: See also https://github.com/eustasy/labs-hash-check

	$Sitewide_Security_Limit = true; // Enable or disable Security Limits
	$Sitewide_Security_Limit_Time = '300'; // Time in seconds to count attempts for
	$Sitewide_Security_Limit_Attempts = '3'; // Number of attempts before blocking or requiring anti-spam
	// Action to take when
	// 'Captcha' = Require Captcha. (Requires ReCaptcha Keys)
	// 'SweetCaptcha' = Require SweetCaptcha. (Requires SweetCaptcha Keys)
	// 'Block' = Block attempts until the limit is passed
	$Sitewide_Security_Limit_Action = 'Block';

	$Sitewide_Account = 'account';
	$Sitewide_Forum = 'forum';
	$Sitewide_AllowHTML = false;
