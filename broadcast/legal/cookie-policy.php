<?php

	$TextTitle = 'Cookie Policy';
	$WebTitle = 'Cookie Policy &nbsp;&middot;&nbsp; Legal';
	$Canonical = 'legal/cookie-policy';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'cookie policy legal';

	require '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../../header.php'; ?>

		<h2>Cookie Policy</h2>
		<p>To enhance your experience on our sites, many of our web pages use "cookies". Cookies are small text files your browser creates in association with our website, which store your preferences.</p>
		<p>Cookies, by themselves, do not tell us your email address or other personal information unless you choose to provide this information to us by, for example, registering at one of our sites.</p>
		<p>Once you choose to provide a web page with personal information, this information may be linked to the data stored in the cookie. A cookie works like an identification card, it is unique to your computer and can only be read by the website that gave it to you.</p>
		<h3>Opting Out</h3>
		<p>You can opt-out of our tracking cookies using various methods, but use of our sites without implementing these methods implies consent of use.</p>
		<p>You can opt-out of AddThis Social Sharing with <a href="http://www.addthis.com/privacy/opt-out">AddThis Data Collection Opt-Out</a>. Other social cookies may include Google+ and Facebook, from our connection boxes. These are set by those sites, and should be visited to disable, or blocked on your computer (see below).</p>
		<p>You can opt-out of Google Anayltics Tracking, Google Adsense Ad Targeting, or Google +1 Personalisation with <a href="http://www.google.com/policies/privacy/tools/">Google Privacy Tools</a>. Further <a href="https://www.google.com/settings/ads/onweb/">Google Ad Settings</a> are available, and you can also opt-out of other advertisers (which we do not use, but other sites may) with <a href="http://www.aboutads.info/choices/">AboutAds.info Choices</a> and <a href="http://www.youronlinechoices.com/uk/your-ad-choices">Your Ad Choices</a>.</p>
		<p>You can also disable cookies globally or by site in your browser, either <a href="http://windows.microsoft.com/en-GB/windows7/Block-enable-or-allow-cookies">Internet Explorer</a>, <a href="https://support.google.com/chrome/bin/answer.py?hl=en-GB&answer=95647&p=cpn_cookies">Google Chrome</a>, <a href="http://support.mozilla.org/en-US/kb/Blocking%20cookies">Mozilla Firefox</a>, or <a href="http://support.apple.com/kb/HT1677">Apple Safari</a>.</p>
		<div class="section group">
			<div class="col span_1_of_2">
				<h3>Essential Cookies</h3>
				<p>Essential Cookies are used to identify you when you log in, or make a payment.</p>
				<p>They work like a name badge, ID, or backstage pass, letting us know who you are and what you're here for.</p>
				<p>Without them, you will not be able to log in, or make payments on <?php echo $Sitewide_Title; ?> sites.</p>
			</div>
			<div class="col span_1_of_2">
				<h3>Tracking Cookies</h3>
				<p>We use Google Analytics to monitor the usage of our sites, and see what parts of our site are getting the most attention, and why.</p>
				<p>Google Analytics is the industry standard, and typically sets the minimum amount of data required, both to enhance privacy, and speed up the website.</p>
				<p>We also see what countries or operating systems are typically accessing the site, and whether or not they continue browsing after they have entered it, or return later.</p>
			</div>
		</div>
		<div class="section group">
			<div class="col span_1_of_2">
				<h3>Targeting Cookies</h3>
				<p>Targeting Cookies are used to to display adverts relevant to your interests, and ours are run by Google Adsense.</p>
				<p>For example, if you search Google for a manicure, Google will know this, and you may see ads on our site about manicures.</p>
				<p>We never access this data, as the ads themselves retrieve and utilise the information, and they are controlled by Google.</p>
			</div>
			<div class="col span_1_of_2">
				<h3>Social Cookies</h3>
				<p>Social Cookies are primarily used by AddThis for the Sharing of articles and logging your favourite services.</p>
				<p>We never know whether or not you are logged into Facebook or other social networks, nor try to retrieve your information without your specific consent.</p>
			</div>
		</div>

<?php require '../../footer.php'; } ?>
