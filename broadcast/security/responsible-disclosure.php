<?php

	$Title_HTML = 'Responsible Disclosure';
	$Title_Plain = 'Responsible Disclosure';

	$Description_HTML = 'Responsible Disclosure for Simplet';
	$Description_Plain = 'Responsible Disclosure for Simplet';

	$Keywords = 'responsive disclosure';

	$Featured_Image = '';

	$Canonical = 'security/responsible-disclosure';

	$Post_Type = 'Page';
	$Post_Category = '';

	require_once __DIR__.'/../../simplet/request.php';

if ($Request['path'] === $Place['path'].$Canonical) {
	require '../../header.php';
	?>

	<h2>Responsible Disclosure of Security Vulnerabilities</h2>

	<p>We want to keep everyones existing Simplet installations safe, so if you've discovered a security vulnerability in Simplet, we appreciate your help in disclosing it to us in a responsible manner.</p>

	<h3>Rules for you</h3>
	<ul>
		<li>Don’t attempt to gain access to another user’s account or data. If you need an account to hack, create one.</li>
		<li>Don’t perform any attack that could harm the reliability/integrity of our services or data. DDoS/spam attacks are not allowed.</li>
		<li>Don’t publicly disclose a bug before it has been fixed.</li>
		<li>Only test for vulnerabilities on <a href="https://security.simplet.eustasy.org">https://security.simplet.eustasy.org</a></li>
		<li>Never attempt non-technical attacks such as social engineering, phishing, or physical attacks against our employees, users, or infrastructure.</li>
	</ul>

	<h3>Rules for eustasy</h3>
	<ul>
		<li>We will respond as quickly as possible to your submission.</li>
		<li>We will keep you updated as we work to fix the bug you submitted.</li>
		<li>We will not take legal action against you if you play by the rules.</li>
	</ul>

	<h3>What does not qualify?</h3>
	<ul>
		<li>Bugs, such as XSS, that only affect legacy browser/plugin versions.</li>
		<li>Bugs, such as XSS, requiring exceedingly unlikely user interaction.</li>
		<li>Bugs, such as timing attacks, that prove the existence of a private topic or member.</li>
		<li>Disclosure of public information and information that does not present significant risk.</li>
		<li>Bugs that have already been submitted by another user, that we are already aware of, or that have been classified as ineligible.</li>
		<li>Bugs in content/services that are not provided by Simplet. This includes Digital Ocean, Ubuntu, Nginx, PHP, MariaDB, Namesilo, Namecheap, and Comodo.</li>
		<li>Vulnerabilities that eustasy determines to be an accepted risk.</li>
		<li>Scripting or other automation and brute forcing of intended functionality.</li>
		<li>Scripting or other automation and brute forcing of intended functionality.</li>
	</ul>

	<h3>Terms of Program</h3>
	<ul>
		<li>You’re not participating from a country against which the United Kingdom has issued export sanctions or other trade restrictions, including Cuba, Iran, Libya, North Korea, the Sudan and Syria.</li>
		<li>Your participation in the Program will not violate any law applicable to you, or disrupt or compromise any data that is not your own.</li>
		<li>You are solely responsible for any applicable taxes, withholding or otherwise, arising from or relating to your participation in the Program, including from any bounty payments.</li>
		<li>eustasy reserves the right to terminate or discontinue the Program at its discretion.</li>
	</ul>

	<?php
	Responses();
	require '../../footer.php';
}