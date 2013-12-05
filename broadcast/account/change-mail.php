<?php

	$TextTitle = 'Change Mail';
	$WebTitle = 'Change Mail &nbsp;&middot;&nbsp; Account';
	$Canonical = 'account/change-mail';
	$PostType = 'Page';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'change mail account';

	require '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	if (!$Member_Auth) {

		header('Location: /account/login', TRUE, 302);
		die();

	} elseif(isset($_POST['mail'])) {

		$Mail_New = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');

		$Mail_Change = mysqli_query($MySQL_Connection, "UPDATE `Members` SET `Mail`='$Mail_New' WHERE `ID`='$Member_ID'", MYSQLI_STORE_RESULT);
		if (!$Mail_Change) exit('Invalid Query (Mail_Change): ' . mysqli_error($MySQL_Connection));

		header('Location: /account/', TRUE, 302);
		die();

	} else {

		require '../../header.php'; ?>
		<form class="col span_1_of_1" action="" method="post">
			<h2>Change your Mail</h2>
			<div class="section group">
				<div class="col span_1_of_3"><label for="mail"><h3>Mail</h3></label></div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2"><input type="email" name="mail" placeholder="<?php echo $Member_Mail; ?>" value="<?php echo $Member_Mail; ?>" required /></div>
			</div>
			<div class="section group">
				<div class="col span_1_of_3"><br></div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2"><input type="submit" value="Change Mail" /></div>
			</div>
		</form>
		<div class="clear"></div>
		<?php require '../../footer.php';

	}

}

?>
