<?php

	$TextTitle = 'Topic';
	$WebTitle = 'Topic';
	$Canonical = 'forum/topic';
	$PostType = 'Topic';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'topic forum';

	require_once '../../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	$Topic_ID = htmlentities($_GET['topic'], ENT_QUOTES, 'UTF-8');

	$Topic_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE `ID`='$Topic_ID' LIMIT 0, 1", MYSQLI_STORE_RESULT);
	if(!$Topic_Check) exit('Invalid Query (Topic_Check): '.mysqli_error($MySQL_Connection));

	$Topic_Count = mysqli_num_rows($Topic_Check);
	if($Topic_Count==0) {
		require '../../header.php';
		echo '
		<h2>Error: Topic does not exist</h2>
		<p class="textcenter">Try the forum.</p>';
		require '../../footer.php';
	} else {

		require '../../parsedown.php';

		$Topic_Fetch = mysqli_fetch_assoc($Topic_Check);
		$Topic_Status = $Topic_Fetch['Status'];
		$Topic_Title = html_entity_decode($Topic_Fetch['Title'], ENT_QUOTES, 'UTF-8');
		$Topic_Created = date('d M, Y H:i', $Topic_Fetch['Created']);
		$Topic_Modified = $Topic_Fetch['Modified'];

		if($Topic_Status=='Hidden'){
			require '../../header.php';
			echo '
			<h2>Error: Topic is Hidden</h2>
			<p class="textcenter">You may never know what is here...</p>';
			require '../../footer.php';
		} else if($Topic_Status=='Private' && !$Member_Auth) {
			require '../../header.php';
			echo '
			<h2>Error: Topic is private</h2>
			<p class="textcenter">You need to login.</p>';
			require '../../footer.php';

		}

		$TextTitle = $Topic_Title;
		$WebTitle = $Topic_Title.' &nbsp;&middot;&nbsp; Topic &nbsp;&middot;&nbsp; Forum';
		$Canonical = 'forum/topic?topic='.$Topic_ID;
		$Description = $Topic_Title;
		$Keywords = $Topic_Title.' topic forum';

		require '../../header.php';

		echo '
		<h2>'.$Topic_Title.'</h2>';

		$Replies = mysqli_query($MySQL_Connection, "SELECT * FROM `Replies` WHERE `Topic_ID`='$Topic_ID' AND `Status`='Public' ORDER BY `Created` ASC", MYSQLI_STORE_RESULT);
		if (!$Replies) exit('Invalid Query (Replies): '.mysqli_error($MySQL_Connection));

		$Replies_Members_IDs = array();
		$Replies_Members_Names = array();
		$Replies_Members_Avatar = array();

		while($Replies_Fetch = mysqli_fetch_assoc($Replies)) {

			$Reply_Member_ID = $Replies_Fetch['Member_ID'];
			$Reply_Post = Parsedown::instance()->parse(html_entity_decode($Replies_Fetch['Post'], ENT_QUOTES, 'UTF-8'));
			$Reply_Created = date('d M, Y H:i', $Replies_Fetch['Created']);
			$Reply_Modified = $Replies_Fetch['Modified'];

			if(in_array($Reply_Member_ID, $Replies_Members_IDs)) {
				$Replies_Members_Num = array_search($Reply_Member_ID, $Replies_Members_IDs);
				echo '
		<div class="section group darkrow">
			<div class="col span_2_of_12 textcenter"><p>'.$Replies_Members_Names[$Replies_Members_Num].'</p></div>
			<div class="col span_10_of_12 faded textright"><p>'.$Reply_Created.'</p></div>
		</div>
		<div class="section group reply">
			<div class="col span_2_of_12"><img class="avatar" src="http://www.gravatar.com/avatar/'.$Replies_Members_Avatar[$Replies_Members_Num].'?s=248&d=identicon"></div>
			<div class="col span_10_of_12">
				'.$Reply_Post.'
			</div>
		</div>';
			} else {
				$Reply_Member = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE `ID`='$Reply_Member_ID' AND `Status`='Active'", MYSQLI_STORE_RESULT);
				if (!$Reply_Member) exit('Invalid Query (Reply_Member): '.mysqli_error($MySQL_Connection));
				$Reply_Member_Count = mysqli_num_rows($Reply_Member);
				if($Reply_Member_Count == 0) {
					echo '
		<div class="section group darkrow">
			<div class="col span_2_of_12 textcenter"><p>Deactivated</p></div>
			<div class="col span_10_of_12 faded textright"><p>'.$Reply_Created.'</p></div>
		</div>
		<div class="section group reply">
			<div class="col span_2_of_12"><img class="avatar" src="http://www.gravatar.com/avatar/deactivated?s=248&d=mm"></div>
			<div class="col span_10_of_12">
				'.$Reply_Post.'
			</div>
		</div>';
				} else {
					$Replies_Members_ID[] = $Reply_Member_ID;
					$Reply_Member_Fetch = mysqli_fetch_assoc($Reply_Member);
					$Reply_Member_Fetch_Name = $Reply_Member_Fetch['Name'];
					$Replies_Members_Names[] = $Reply_Member_Fetch_Name;
					$Reply_Member_Fetch_Avatar = md5($Reply_Member_Fetch['Mail']);
					$Replies_Members_Avatar[] = $Reply_Member_Fetch_Avatar;
					echo '
		<div class="section group darkrow">
			<div class="col span_2_of_12 textcenter"><p>'.$Reply_Member_Fetch_Name.'</p></div>
			<div class="col span_10_of_12 faded textright"><p>'.$Reply_Created.'</p></div>
		</div>
		<div class="section group reply">
			<div class="col span_2_of_12"><img class="avatar" src="http://www.gravatar.com/avatar/'.$Reply_Member_Fetch_Avatar.'?s=248&d=identicon"></div>
			<div class="col span_10_of_12">
				'.$Reply_Post.'
			</div>
		</div>';
				}
			}
		}

		if($Member_Auth) {
			echo '
		<div class="clear"></div>
		<div class="section group">
			<form action="reply" method="post">
				<input type="hidden" name="topic_id" value="'.$Topic_ID.'" />
				<div class="col span_2_of_12"><br></div>
				<div class="col span_10_of_12">
					<h3>Post a Reply</h3>
					<textarea name="post" required></textarea>
				</div>
				<div class="col span_2_of_12"><br></div>
				<div class="col span_8_of_12">
					<p><small>If you wish, you can use Markdown for formatting.<br>
					Markdown can be used to make [<a href="#">links</a>](http://example.com),<br>
					<strong>**bold text**</strong>, <em>_italics_</em> and <code>`code`</code>.</small></p>
				</div>
				<div class="col span_2_of_12">
					<input type="submit" value="Reply" />
				</div>
			</form>
		</div>';
		} else {
			echo '
		<h3>You must <a href="'.$Request['scheme'].'://'.$Request['host'].'/account/login">login</a> to post a reply.</h3>';
		}

		require '../../footer.php';
	}
} ?>
