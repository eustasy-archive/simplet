<?php

	if(isset($_POST['action']) && $_POST['action']=='comment') {

		if(!$Member_Auth) {
			$Error = 'You cannot comment, you are not logged in.';
		} else {
			if(!isset($_POST['canonical']) || empty($_POST['canonical'])) {
				$Error = 'Could not determine which post you wanted to comment on.';
			} else if(!isset($_POST['post']) || empty($_POST['post'])) {
				$Error = 'You didn\'t enter a comment.';
			} else {

				$Comment_Canonical = trim(htmlentities($_POST['canonical'], ENT_QUOTES, 'UTF-8'));
				$Comment_Post = trim(htmlentities($_POST['post'], ENT_QUOTES, 'UTF-8'));

				if(empty($Comment_Canonical)) {
					$Error = 'Could not determine which post you wanted to comment on.';
				} else if(empty($Comment_Post)) {
					$Error = 'You didn\'t enter a comment.';
				} else {

					$Time = time();
					$Comment_Status = 'Public';

					$Comment_New = mysqli_query($MySQL_Connection, "INSERT INTO `Comments` (`Member_ID`, `Canonical`, `Status`, `Post`, `Created`, `Modified`) VALUES ('$Member_ID', '$Comment_Canonical', '$Comment_Status', '$Comment_Post', '$Time', '$Time')", MYSQLI_STORE_RESULT);
					if (!$Comment_New) exit('Invalid Query (Comment_New): '.mysqli_error($MySQL_Connection));

				}

			}
		}
	}

	$Comments = mysqli_query($MySQL_Connection, "SELECT * FROM `Comments` WHERE `Canonical`='$Canonical' AND `Status`='Public' ORDER BY `Created` ASC", MYSQLI_STORE_RESULT);
	if(!$Comments) exit('Invalid Query (Comments): '.mysqli_error($MySQL_Connection));

	$Comments_Count = mysqli_num_rows($Comments);
	if($Comments_Count==0) {
		echo '
			<hr>
			<h3>No Comments to Display.</h3>';
	} else {
		echo '
			<hr>
			<h3>'.$Comments_Count.' Comments</h3>';

		require 'parsedown.php';

		$Comments_Members_IDs = array();
		$Comments_Members_Names = array();
		$Comments_Members_Avatar = array();

		while($Comments_Fetch = mysqli_fetch_assoc($Comments)) {

			$Comments_Member_ID = $Comments_Fetch['Member_ID'];
			$Comments_Post = Parsedown::instance()->parse(html_entity_decode($Comments_Fetch['Post'], ENT_QUOTES, 'UTF-8'));
			$Comments_Created = $Comments_Fetch['Created'];
			$Comments_Modified = $Comments_Fetch['Modified'];

			// TODO
			// The Members in the cache should be changed to Object Oriented code

			if(in_array($Comments_Member_ID, $Comments_Members_IDs)) {
				$Comments_Members_Num = array_search($Comments_Member_ID, $Comments_Members_IDs);
				$Comments_Store_Name = $Comments_Members_Names[$Comments_Members_Num];
				$Comments_Store_Avatar = $Comments_Members_Avatar[$Comments_Members_Num];
			} else {
				$Comments_Member = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE `ID`='$Comments_Member_ID' AND `Status`='Active'", MYSQLI_STORE_RESULT);
				if (!$Comments_Member) exit('Invalid Query (Comments_Member): '.mysqli_error($MySQL_Connection));
				$Comments_Member_Count = mysqli_num_rows($Comments_Member);
				if($Comments_Member_Count == 0) {
					$Comments_Store_Name = 'Deactivated';
					$Comments_Store_Avatar = 'http://www.gravatar.com/avatar/deactivated?s=248&d=mm';
				} else {
					$Comments_Member_Fetch = mysqli_fetch_assoc($Comments_Member);
					$Comments_Store_Name = $Comments_Member_Fetch['Name'];
					$Comments_Store_Avatar = 'http://www.gravatar.com/avatar/'.md5($Comments_Member_Fetch['Mail']).'?s=248&d=identicon';
				}
				$Comments_Members_ID[] = $Comments_Member_ID;
				$Comments_Members_Names[] = $Comments_Store_Name;
				$Comments_Members_Avatar[] = $Comments_Store_Avatar;
			}

			echo '
				<div class="section group darkrow">
					<div class="col span_2_of_12 textcenter';
			if($Comments_Store_Name === 'Deactivated') echo ' faded';
			echo '"><p>'.$Comments_Store_Name.'</p></div>
					<div class="col span_10_of_12 textright"><p>';
			if($Comments_Modified > $Comments_Created) echo '<span class="faded edited">edited '.date('d M, Y H:i', $Comments_Modified).' &nbsp;&middot;&nbsp; </span>';
			echo date('d M, Y H:i', $Comments_Created).'</p></div>
				</div>
				<div class="section group reply">
					<div class="col span_2_of_12"><img class="avatar" src="'.$Comments_Store_Avatar.'"></div>
					<div class="col span_10_of_12">
						'.$Comments_Post.'
					</div>
				</div>';
		}

	}

	if($Member_Auth) {
		if($Comments_Count > 0) echo '
			<hr>';
		echo '
			<div class="clear"></div>
			<form action="" method="post">
				<div class="section group">
					<input type="hidden" name="action" value="comment" />
					<input type="hidden" name="canonical" value="'.$Canonical.'" />
					<div class="col span_1_of_12"><br></div>
					<div class="col span_10_of_12">
						<h3>Post a Comment</h3>
						<textarea name="post" required></textarea>
					</div>
					<div class="col span_1_of_12"><br></div>
				</div>
				<div class="section group">
					<div class="col span_1_of_12"><br></div>
					<div class="col span_8_of_12">
						<p><small>If you wish, you can use Markdown for formatting.<br>
						Markdown can be used to make [<a href="#">links</a>](http://example.com),<br>
						<strong>**bold text**</strong>, <em>_italics_</em> and <code>`code`</code>.</small></p>
					</div>
					<div class="col span_2_of_12">
						<input type="submit" value="Comment" />
					</div>
					<div class="col span_1_of_12"><br></div>
				</div>
			</form>';
	} else {
		echo '
				<h3>You must <a href="'.$Request['scheme'].'://'.$Request['host'].'/account/login">login</a> to post a reply.</h3>';
	}
