<?php

	$TextTitle = 'Copyright Notice';
	$WebTitle = 'Copyright Notice &nbsp;&middot;&nbsp; Legal';
	$Canonical = 'legal/copyright-notice';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'copyright notice legal';

	require '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../../header.php'; ?>

		<h2>Copyright Notice</h2>
		<p>The contents of nearly all <?php echo $Sitewide_Title; ?> sites are licensed under a <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution Non-Commercial Share-Alike 3.0 Unported (BY-NC-SA 3.0)</a>. Please see each individual site for details.
			<span class="floatright">
				<a class="cc" href="<?php echo $Request['host']; ?>/licenses#cc" title="Creative Commons">c</a>
				<a class="cc" href="<?php echo $Request['host']; ?>licenses#by" title="Attribution">b</a>
				<a class="cc" href="<?php echo $Request['host']; ?>licenses#nc" title="Non-Commercial">n</a>
				<a class="cc" href="<?php echo $Request['host']; ?>licenses#sa" title="Share-Alike">a</a>
			</span>
		</p>
		<p>Occasionally some sites will be licensed under a <a href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution 3.0 Unported (BY 3.0)</a>. The license emblem will appear like this.
			<span class="floatright">
				<a class="cc" href="<?php echo $Request['host']; ?>licenses#cc" title="Creative Commons">c</a>
				<a class="cc" href="<?php echo $Request['host']; ?>licenses#by" title="Attribution">b</a>
			</span>
		</p>
		<p>The <?php echo $Sitewide_Title; ?> name, brand, logo, and motif are Copyright &copy; of <?php echo $Sitewide_Title; ?> 2013, all rights reserved. You may not utilize these items in any way which may be perceived as an endorsement of your site or service.</p>
		<p>Any redistribution or reproduction of part or all of the contents in any form is allowed under the condition of attribution via linked text such as "Source", "Reference", "Via" or "Original from <?php echo $Sitewide_Title; ?>" for all non-commercial purposes.</p>
		<p>Conditions are wavered when:</p>
		<div class="section group">
			<div class="col span_1_of_6"><br></div>
			<div class="col span_4_of_6">
				<ul>
					<li>You print or download to a local hard disk extracts for your personal & non-commercial use only.</li>
					<li>You copy the content to individual third parties for their personal use, but only if you acknowledge the website as the source of the material.</li>
					<li>You may not, except with our express written permission, distribute or commercially exploit the content.</li>
				</ul>
			</div>
			<div class="col span_1_of_6"><br></div>
		</div>

<?php require '../../footer.php'; } ?>
