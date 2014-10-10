<?php

	$Title_HTML = 'Security Levels';
	$Title_Plain = 'Security Levels';

	$Description_HTML = 'Security Levels for Simplet';
	$Description_Plain = 'Security Levels for Simplet';

	$Keywords = 'security levels';

	$Featured_Image = '';

	$Canonical = 'security/levels';

	$Post_Type = 'Page';
	$Post_Category = '';

	require_once __DIR__.'/../../simplet/request.php';

if ($Request['path'] === $Place['path'].$Canonical) {
	require '../../header.php';
	?>

	<h2>Security Levels</h2>

	<h3>Level 1 - Passwords, Salts, Sessions, Settings, and the Site</h3>
	<ul class="task-list">
		<li>Retrieving another members Password, without brute-force.</li>
		<li>Retrieving another members Password Hash, without brute-force.</li>
		<li>Retrieving any members Salt.</li>
		<li>Modifying your own or another users Member ID.</li>
		<li>Modifying another members Name, Mail, Pass or Salt.</li>
		<li>Commenting as a member other than youself.</li>
		<li>Deleting a member other than yourself.</li>
		<li>Granting yourself or another user administrative rights.</li>
		<li>Adding or removing any member from any group.</li>
		<li>Retrieving any of the Database Settings, including host, user, pass, or name.</li>
		<li>Modifying the site to display content (excluding links) of a malicious or misleading nature so that the content does not appear to be user contributed.</li>
		<li>Modifying the Site settings in any way.</li>
		<li>Reveal the contents of a file meant to be executed rather than viewed.</li>
	</ul>
	<h6>These breaches could potentially be accomplished with common attacks such as:</h6>
	<ul class="task-list">
		<li>Cross-Site Scripting (XSS)</li>
		<li>SQL Injection</li>
		<li>Code Execution (possibly with assistance from Path Disclosure)</li>
		<li>Memory Corruption</li>
		<li>Arbitrary File (Addition, Modification, Execution and Deletion)</li>
		<li>Local or Remote File Inclusion</li>
	</ul>

	<h3>Level 2 - Underlying Infrastructure</h3>
	<ul class="task-list">
		<li>Reveal the Version of Simplet outside of the Administration Area.</li>
		<li>Reveal the real path.</li>
		<li>Execute the contents of a file outside of the broadcast directory meant to be viewed.</li>
	</ul>
	<h6>These breaches could potentially be accomplished with common attacks such as:</h6>
	<ul class="task-list">
		<li>Path Disclosure</li>
		<li>Code Execution</li>
	</ul>
	<h6>Note: With the possible exception of Memory Corruption, all Level 1 &amp; 2 attacks should be halted by proper input sanitization.</h6>

	<h3>Level 3 - Scaling Constraints</h3>
	<h6><em>No bounties will be awarded for Level 3 issues.</em></h6>
	<ul class="task-list">
		<li>Site Availability</li>
	</ul>
	<h6>These breaches could potentially be accomplished with common attacks such as:</h6>
	<ul class="task-list">
		<li>Distributed Denial of Service (DDoS)</li>
	</ul>

	<h3>Level 4 - Known Issues</h3>
	<h6><em>No bounties will be awarded for Level 4 issues.</em></h6>
	<p><strong>Cross-Site Request Forgery:</strong> Many forms can be submitted from foreign pages. Referral tracking is unreliable, so all forms should also carry a single-use authentication token, implemented using the RunOnce functions. <strong>Known to affect all versions.</strong></p>
	<p><strong>Insecure content loading:</strong> The Responses system, among others, makes use of the service "<a href="https://en.gravatar.com/">Gravatar</a>". Some content is loaded over a fixed http conection, without proper encryption. <strong>Known to affect all versions.</strong></p>
	<p><strong>XML Conversion oddities:</strong>Because numeric keys are not valid in XML, the API converts the items to <code>"item###"</code>, which could inadvertently cause issues with Member_IDs and other automatically generated values that have a small chance of being entirely numeric. <code>(10/36)^12 = 2.11042533e-7</code> <strong>Known to affect 4.1+</strong></p>

	<h3>Level 5 - Fixed Issues</h3>
	<h6><em>Fixed in development or even a previous release. No bounties.</em></h6>
	<p><strong>External Redirection:</strong>Members can be redirected to external site after prompting for a session. Should be optional, and non-default, labelled as a potential security risk. <strong>Partial Fix in version 4. Disabled entirely, possibly an option in a later release.</strong></p>

	<?php
	Responses();
	require '../../footer.php';
}