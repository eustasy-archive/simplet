<?php

	$TextTitle = 'Account';
	$WebTitle = 'Account';
	$Canonical = 'account/';
	$PostType = 'Page';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'account';

	require_once '../../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

	if (!$Member_Auth) { // Are you logged in already?

		header('Location: /account/login', TRUE, 302);
		die();

	} else {

		require '../../header.php';

?>

		<div class="section group">
			<div class="col span_1_of_8"><br></div>
			<div class="col span_6_of_8">
				<h2 class="textleft">Hello <?php echo $Member_Name; ?>.<a class="floatright" href="change-name">Change Name</a></h2>
				<h3 class="textleft"><?php echo $Member_Mail; ?><a class="floatright" href="change-mail">Change Mail</a></h3>
				<h3><a class="floatright" href="change-pass">Change Pass</a></h3>
				<p>Your Member ID is <?php echo $Member_ID; ?>. This cannot be changed. <a class="floatright red" href="delete">Delete Account</a></p>
			</div>
			<div class="col span_1_of_8"><br></div>
		</div>

<?php require '../../footer.php'; } } ?>
