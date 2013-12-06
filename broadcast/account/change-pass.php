<?php

	$TextTitle = 'Change Pass';
	$WebTitle = 'Change Pass &nbsp;&middot;&nbsp; Account';
	$Canonical = 'account/change-pass';
	$PostType = 'Page';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'change pass account';

	require_once '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	if (!$Member_Auth) {

		header('Location: /account/login', TRUE, 302);
		die();

	} elseif(isset($_POST['pass'])) {

		$Pass_New = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');

		$Salt = stringGenerator();

		$Pass_Hash = passHash($Pass_New, $Salt);

		$Pass_Change = mysqli_query($MySQL_Connection, "UPDATE `Members` SET `Pass`='$Pass_Hash', `Salt`='$Salt' WHERE `ID`='$Member_ID'", MYSQLI_STORE_RESULT);
		if (!$Pass_Change) exit('Invalid Query (Pass_Change): ' . mysqli_error($$MySQL_Connection));

		header('Location: /account/', TRUE, 302);
		die();

	} else {

		require '../../header.php'; ?>
		<form class="col span_1_of_1" action="" method="post">
			<h2>Change your Pass</h2>
			<div class="section group">
				<div class="col span_1_of_3"><label for="pass"><h3>Pass</h3></label></div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2"><input type="password" name="pass" placeholder="Qwerty12345" required /></div>
			</div>
			<div class="section group">
				<div class="col span_1_of_3"><br></div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2"><input type="submit" value="Change Pass" /></div>
			</div>
		</form>
		<div class="clear"></div>
		<?php require '../../footer.php';

	}

}

?>
