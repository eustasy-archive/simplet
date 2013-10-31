<?php

	$TextTitle = 'Terms of Service';
	$WebTitle = 'Terms of Service &nbsp;&middot;&nbsp; Legal';
	$Canonical = 'legal/terms-of-service';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'terms of service legal';

	require '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../../header.php'; ?>

		<h2>Terms of Service</h2>
		<p>If you continue to browse & use this website, you are agreeing to comply with & be bound by the following Terms & Conditions of use, which together with our Privacy Policy govern <?php echo $Sitewide_Title; ?>’s relationship with you in relation to this website. If you disagree with any part of these terms & conditions, please do not use our website.</p>
		<div class="section group">
			<div class="col span_1_of_6"><br></div>
			<div class="col span_4_of_6">
				<ul>
					<li>The term ‘<?php echo $Sitewide_Title; ?>’ or ‘us’ or ‘we’ refers to the owner of the website.</li>
					<li>The term ‘you’ refers to the user or viewer of our website.</li>
				</ul>
			</div>
			<div class="col span_1_of_6"><br></div>
		</div>
		<p>The use of this website is subject to the following terms of use:</p>
		<div class="section group">
			<div class="col span_1_of_6"><br></div>
			<div class="col span_4_of_6">
				<ul>
					<li>The content of the pages of this website is for your general information & use only. It is subject to change without notice.</li>
					<li>Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information & materials found or offered on this website for any particular purpose. You acknowledge that such information & materials may contain inaccuracies or errors & we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</li>
					<li>Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</li>
					<li>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance & graphics. Reproduction is prohibited other than in accordance with the Copyright Notice, which forms part of these terms & conditions.</li>
					<li>All trademarks reproduced in this website, which are not the property of, or licensed to the operator, are acknowledged on the website.</li>
					<li>Unauthorised use of this website may give rise to a claim for damages &/or be a criminal offence.</li>
					<li>From time to time, this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</li>
					<li>Your use of this website & any dispute arising out of such use of the website is subject to the laws of England, Northern Ireland, Scotland & Wales.</li>
				</ul>
			</div>
			<div class="col span_1_of_6"><br></div>
		</div>

<?php require '../../footer.php'; } ?>
