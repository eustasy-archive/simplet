<?php

	$TextTitle = 'Privacy Policy';
	$WebTitle = 'Privacy Policy &nbsp;&middot;&nbsp; Legal';
	$Canonical = 'legal/privacy-policy';
	$PostType = 'Page';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'privacy policy legal';

	require_once '../../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

	require '../../header.php'; ?>

		<h2>Privacy Policy</h2>
		<p>This privacy policy sets out how <?php echo $Sitewide_Title; ?> uses & protects any information that you give <?php echo $Sitewide_Title; ?> when you use our websites.</p>
		<p><?php echo $Sitewide_Title; ?> is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using our websites, then you can be assured that it will only be used in accordance with this privacy statement.</p>
		<p><?php echo $Sitewide_Title; ?> may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes. This policy is effective from 01/01/2013.</p>
		<div clas="section group">
			<div class="col span_1_of_2">
				<h3>Security</h3>
				<p>We are committed to ensuring that your information is secure. In order to prevent unauthorized access or disclosure, we have put in place suitable physical, electronic & managerial procedures to safeguard & secure the information we collect online.</p>
				<p>We are committed to ensuring that your information is secure. In order to prevent unauthorized access or disclosure, we have put in place suitable physical, electronic & managerial procedures to safeguard & secure the information we collect online.</p>
			</div>
			<div class="col span_1_of_2">
				<h3>Links</h3>
				<p>Our websites may contain links to other websites of interest. However, once you have used these links to leave one of our sites, you should note that we do not have any control over that other website.</p>
				<p>Therefore, we cannot be responsible for the protection & privacy of any information which you provide whilst visiting such sites & such sites are not governed by this privacy statement. You should exercise caution & look at the privacy statement applicable to the website in question.</p>
			</div>
		</div>
		<div class="section group">
			<div class="col span_1_of_2">
				<h3>Promise</h3>
				<p>We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so.</p>
				<p>We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen.</p>
			</div>
			<div class="col span_1_of_2">
				<h3>Control</h3>
				<p>You may access, change, remove, or request details of personal information which we hold about you under the Data Protection Act 1998. If you would like a copy of the information held on you, you can access it at any time via the administration options on the sites you accessed, or by writing to &#113;&#117;&#101;&#114;&#121;&#064;&#101;&#117;&#115;&#116;&#097;&#115;&#121;&#046;&#099;&#111;&#046;&#117;&#107;</p>
				<p>If you believe that any information we are holding on you is incorrect or incomplete, please write to or e-mail us as soon as possible, at the above address. We will promptly correct any information found to be incorrect.</p>
			</div>
		</div>

<?php require '../../footer.php'; } ?>
