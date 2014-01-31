<?php

	$TextTitle = 'Website Disclaimer';
	$WebTitle = 'Website Disclaimer &nbsp;&middot;&nbsp; Legal';
	$Canonical = 'legal/website-disclaimer';
	$PostType = 'Page';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'website disclaimer legal';

	require_once '../../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

	require '../../header.php'; ?>

		<h2>Website Disclaimer</h2>
		<p>The information contained in this website is for general information purposes only. The information is provided by <?php echo $Sitewide_Title; ?> & while we endeavour to keep the information up to date & correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</p>
		<p>In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>
		<p>Through this website you are able to link to other websites which are not under the control of <?php echo $Sitewide_Title; ?>. We have no control over the nature, content & availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</p>
		<p>Every effort is made to keep the website up & running smoothly. However, <?php echo $Sitewide_Title; ?> takes no responsibility for, & will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.</p>

<?php require '../../footer.php'; } ?>
