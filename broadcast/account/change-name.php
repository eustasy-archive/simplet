<?php

	$TextTitle = 'Change Name';
	$WebTitle = 'Change Name &nbsp;&middot;&nbsp; Account';
	$Canonical = 'account/change-name';
	$PostType = 'Page';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'change name account';

	require_once '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	if (!$Member_Auth) {

		header('Location: /account/login', TRUE, 302);
		die();

	} elseif(isset($_POST['name'])) {

		$Name_New = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');

		$Check_One = strpos(strtolower($Name_New),'eustasy');
		$Check_Two = strpos(strtolower($Name_New),'moderator');
		$Check_Three = strpos(strtolower($Name_New),'admin');

		if($Check_One === false && $Check_Two === false && $Check_Three === false) {

			$Name_Change = mysqli_query($MySQL_Connection, "UPDATE `Members` SET `Name`='$Name_New' WHERE `ID`='$Member_ID'", MYSQLI_STORE_RESULT);
			if (!$Name_Change) exit('Invalid Query (Name_Change): ' . mysqli_error($MySQL_Connection));

			header('Location: /account/', TRUE, 302);
			die();

		} else {
			$Error = 'Your name cannot contain the terms eustasy, moderator or admin.';
		}

		if($Error) {
			require '../../header.php';
			echo '<h2>Change Name Error</h2>';
			echo '<h3>' . $Error . '</h3>';
			require '../../footer.php';
		}

	} else {

		require '../../header.php'; ?>
		<form class="col span_1_of_1" action="" method="post">
			<h2>Change your Name</h2>
			<div class="section group">
				<div class="col span_1_of_3"><label for="name"><h3>Name</h3></label></div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2"><input type="text" name="name" placeholder="<?php echo $Member_Name; ?>" value="<?php echo $Member_Name; ?>" required /></div>
			</div>
			<div class="section group">
				<div class="col span_1_of_3"><br></div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2"><input type="submit" value="Change Name" /></div>
			</div>
		</form>
		<div class="clear"></div>
		<?php require '../../footer.php';

	}

}

?>
