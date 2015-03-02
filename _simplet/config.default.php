<?php

	// This is the Title of your Site
	$Sitewide['Title'] = 'The Title of your Site';

	// And the tagline
	$Sitewide['Tagline'] = 'The Tagline of your site.';

	// This is the Base URL of your Site
	// Do NOT include a trailing slash
	// Do include a http:// or https://
	$Sitewide['Root'] = 'http://simplet.com';
	// $Sitewide['Root'] = 'http://example.com';
	// $Sitewide['Root'] = 'https://something.000space.com';

	// Allow or disallow Signups
	$Sitewide['Signups'] = true;

	// Strip .php Extensions
	// IMPORTANT: Requires additional server-side setup for extension-less PHP.
	$Backend['Strip PHP from URLs'] = true;

	// Debug
	$Backend['Debug'] = true;
	$Sitewide['Debug'] = true;

	// Enable or Disable Browning Mail
	// Requires Setup in libs
	$Libs['Browning'] = true;

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
	$Sitewide_Security_Hash_Method = 'sha512';
	// Note: Could also use whirlpool.
	// Note: See also https://github.com/eustasy/labs-hash-check
	$Sitewide_Security_Hash_Iterations = 1000;
	$Sitewide_Security_Hash_Current = 2;

	$Sitewide_Security_Limit = true; // Enable or disable Security Limits
	$Sitewide_Security_Limit_Time = '300'; // Time in seconds to count attempts for
	$Sitewide_Security_Limit_Attempts = '3'; // Number of attempts before blocking or requiring anti-spam
	// Action to take when
	// 'Captcha' = Require Captcha. (Requires ReCaptcha Keys)
	// 'SweetCaptcha' = Require SweetCaptcha. (Requires SweetCaptcha Keys)
	// 'Block' = Block attempts until the limit is passed
	$Sitewide_Security_Limit_Action = 'Block';
	$Sitewide_Security_Password_Length = 10;

	$Sitewide['Account'] = '/account';
	$Sitewide['Forum'] = '/forum';
	$Sitewide['Security']['AllowHTML'] = false;
	$Sitewide['Security']['HonorDNT'] = true;
	$Sitewide['AutoLoad']['Functions'] = true;
	$Sitewide['AutoLoad']['Libs'] = true;