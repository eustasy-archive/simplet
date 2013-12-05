<?php

	$TextTitle = 'Legal';
	$WebTitle = 'Legal';
	$Canonical = 'legal/';
	$PostType = 'Index';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'legal copyright notice cookie policy privacy terms service website disclaimer';

	require '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../../header.php'; ?>

		<h2>Legal</h2>
		<p>These sections govern the legal codes, rules, and regulations tied to eustasy sites, their conduct, and compliance with applicable laws.</p>

		<div class="section group">
			<div class="col span_1_of_2">
				<h3><a href="<?php echo $Request['host']; ?>/legal/copyright-notice">Copyright Notice</a></h3>
			</div>
			<div class="col span_1_of_2">
				<h3><a href="<?php echo $Request['host']; ?>/legal/cookie-policy">Cookie Policy</a></h3>
			</div>
		</div>
		<div class="section group">
			<div class="col span_1_of_2">
				<h3><a href="<?php echo $Request['host']; ?>/legal/privacy-policy">Privacy Policy</a></h3>
			</div>
			<div class="col span_1_of_2">
				<h3><a href="<?php echo $Request['host']; ?>/legal/terms-of-service">Terms of Service</a></h3>
			</div>
		</div>
		<div class="section group">
			<div class="col span_1_of_2">
				<h3><a href="<?php echo $Request['host']; ?>/legal/website-disclaimer">Website Disclaimer</a></h3>
			</div>
		</div>

<?php require '../../footer.php'; } ?>
