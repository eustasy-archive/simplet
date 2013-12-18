<?php

	$TextTitle = 'Forum';
	$WebTitle = 'Forum';
	$Canonical = 'forum';
	$PostType = 'Forum';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'forum';

	require_once '../request.php';
	$Header = '../header.php';
	$Footer = '../footer.php';
	$Parsedown = '../parsedown.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	if(isset($_POST['action'])) {

		if(!$Member_Auth) {
    		header('HTTP/1.1 401 Unauthorized');
			$Error = 'You are not logged in.';

		} else if($_POST['action']=='reply') {

			if(!isset($_POST['topic_slug']) || empty($_POST['topic_slug'])) {
				$Error = 'No Topic Slug Set.';

			} else if(!isset($_POST['post']) || empty($_POST['post'])) {
				$Error = 'You didn\'t enter a reply.';

			} else {

				$Topic_Slug = trim(htmlentities($_POST['topic_slug'], ENT_QUOTES, 'UTF-8'));
				$Reply_Post = trim(htmlentities($_POST['post'], ENT_QUOTES, 'UTF-8'));

				if(empty($Topic_Slug)) {
					$Error = 'No Topic Slug Set.';

				} else if(empty($Reply_Post)) {
					$Error = 'You didn\'t enter a reply.';

				} else {

					$Time = time();
					$Reply_Status = 'Public';

					$Reply_New = mysqli_query($MySQL_Connection, "INSERT INTO `Replies` (`Member_ID`, `Topic_Slug`, `Status`, `Post`, `Created`, `Modified`) VALUES ('$Member_ID', '$Topic_Slug', '$Reply_Status', '$Reply_Post', '$Time', '$Time')", MYSQLI_STORE_RESULT);
					if (!$Reply_New) exit('Invalid Query (Reply_New): '.mysqli_error($MySQL_Connection));

					header('Location: /'.$Canonical.'?topic='.$Topic_Slug, TRUE, 302);
					die();

				}

			}

		} else if($_POST['action']=='topic') {

			if(!isset($_POST['title']) || empty($_POST['title'])) {
				$Error = 'No Topic Set.';

			} else if(!isset($_POST['category']) || empty($_POST['category'])) {
				$Error = 'No Category Set.';

			} else {

					$Topic_Title = trim(htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8'));
					$Topic_Category = trim(htmlentities($_POST['category'], ENT_QUOTES, 'UTF-8'));

					if(isset($_POST['post']) || !empty($_POST['post'])) {
						$Topic_Post = trim(htmlentities($_POST['post'], ENT_QUOTES, 'UTF-8'));
					} else {
						$Topic_Post = false;
					}

					$Topic_Slug = strtolower($_POST['title']);
					$Topic_Slug = htmlentities($Topic_Slug, ENT_QUOTES, 'UTF-8');
					$Topic_Slug = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $Topic_Slug);
					$Topic_Slug = html_entity_decode($Topic_Slug, ENT_QUOTES, 'UTF-8');
					$Topic_Slug = strip_tags($Topic_Slug);
					$Topic_Slug = stripslashes($Topic_Slug);
					$Topic_Slug = str_replace('\'', '', $Topic_Slug);
					$Topic_Slug = str_replace('"', '', $Topic_Slug);
					$Topic_Slug = preg_replace('/[^a-z0-9]+/', '-', $Topic_Slug);
					$Topic_Slug = strtr($Topic_Slug, 'àáâãäåòóôõöøèéêëðçìíîïùúûüñšž', 'aaaaaaooooooeeeeeciiiiuuuunsz');
					$Topic_Slug = preg_replace(array('/\s/', '/--+/', '/---+/'), '-', $Topic_Slug);
					$Topic_Slug = trim($Topic_Slug, '-');

					$Time = time();
					$Topic_Status = 'Public';

					// TODO Uniqueness check $Topic_Slug

					$Topic_New = mysqli_query($MySQL_Connection, "INSERT INTO `Topics` (`Member_ID`, `Status`, `Category`, `Slug`, `Title`, `Created`, `Modified`) VALUES ('$Member_ID', '$Topic_Status', '$Topic_Category', '$Topic_Slug', '$Topic_Title', '$Time', '$Time')", MYSQLI_STORE_RESULT);
					if (!$Topic_New) exit('Invalid Query (Topic_New): '.mysqli_error($MySQL_Connection));

					if($Topic_Post) {
						$Reply_Status = 'Public';
						$Topic_First = mysqli_query($MySQL_Connection, "INSERT INTO `Replies` (`Member_ID`, `Topic_Slug`, `Status`, `Post`, `Created`, `Modified`) VALUES ('$Member_ID', '$Topic_Slug', '$Reply_Status', '$Topic_Post', '$Time', '$Time')", MYSQLI_STORE_RESULT);
						if (!$Topic_First) exit('Invalid Query (Topic_First): '.mysqli_error($MySQL_Connection));
					}

					header('Location: /'.$Canonical.'?topic='.$Topic_Slug, TRUE, 302);
					die();

				}

		}

		if(isset($Error) && $Error) {
			require $Header;
			echo '<h2>Error: ';
			if(isset($Error) && $Error) echo $Error;
			else echo 'Simplet can\'t tell what went wrong.';
			echo '</h2>';
			echo '<h3>Simplet encountered an error processing your reply.</h3>';
			require $Footer;
		}

	} else if(isset($_GET['topic']) && !empty($_GET['topic'])) {

		$Topic_Slug = htmlentities($_GET['topic'], ENT_QUOTES, 'UTF-8');

		$Topic_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE `Slug`='$Topic_Slug' LIMIT 0, 1", MYSQLI_STORE_RESULT);
		if(!$Topic_Check) exit('Invalid Query (Topic_Check): '.mysqli_error($MySQL_Connection));

		$Topic_Count = mysqli_num_rows($Topic_Check);
		if($Topic_Count==0) {
			header('HTTP/1.1 404 Not Found');
			require $Header;
			echo '
			<h2>Error: Topic does not exist</h2>
			<p class="textcenter">Try the forum.</p>';
			require $Footer;
		} else {

			require $Parsedown;

			$Topic_Fetch = mysqli_fetch_assoc($Topic_Check);
			$Topic_Status = $Topic_Fetch['Status'];
			$Topic_Title = html_entity_decode($Topic_Fetch['Title'], ENT_QUOTES, 'UTF-8');
			$Topic_Created = date('d M, Y H:i', $Topic_Fetch['Created']);
			$Topic_Modified = $Topic_Fetch['Modified'];

			if($Topic_Status=='Hidden'){
				header('HTTP/1.1 403 Forbidden');
				require $Header;
				echo '
				<h2>Error: Topic is Hidden</h2>
				<p class="textcenter">You may never know what is here...</p>';
				require $Footer;
			} else if($Topic_Status=='Private' && !$Member_Auth) {
    			header('HTTP/1.1 401 Unauthorized');
				require $Header;
				echo '
				<h2>Error: Topic is private</h2>
				<p class="textcenter">You need to login.</p>';
				require $Footer;
			} else {

				$TextTitle = $Topic_Title;
				$WebTitle = $Topic_Title.' &nbsp;&middot;&nbsp; Topic &nbsp;&middot;&nbsp; Forum';
				$Canonical = $Canonical.'?topic='.$Topic_Slug;
				$Description = $Topic_Title;
				$Keywords = $Topic_Title.' topic forum';

				require $Header;

				echo '
				<h2>'.$Topic_Title.'</h2>';

				$Replies = mysqli_query($MySQL_Connection, "SELECT * FROM `Replies` WHERE `Topic_Slug`='$Topic_Slug' AND `Status`='Public' ORDER BY `Created` ASC", MYSQLI_STORE_RESULT);
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
					<form action="" method="post">
						<input type="hidden" name="action" value="reply" />
						<input type="hidden" name="topic_slug" value="'.$Topic_Slug.'" />
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

				require $Footer;
			}
		}

	} else if(isset($_GET['category']) && !empty($_GET['category'])) {

		$Category_Slug = trim(htmlentities($_GET['category'], ENT_QUOTES, 'UTF-8'));

		if($Member_Auth) {
			$Categories = mysqli_query($MySQL_Connection, "SELECT * FROM `Categories` WHERE NOT `Status`='Hidden' AND `Slug`='$Category_Slug' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
		} else {
			$Categories = mysqli_query($MySQL_Connection, "SELECT * FROM `Categories` WHERE NOT `Status`='Hidden' AND NOT `Status`='Private' AND `Slug`='$Category_Slug' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
		}

		if (!$Categories) exit('Invalid Query (Categories): '.mysqli_error($MySQL_Connection));

		$Categories_Count = mysqli_num_rows($Categories);
		if ($Categories_Count == 0) {
			if($Member_Auth) {
				header('HTTP/1.1 404 Not Found');
				require $Header;
				echo '<h3>There is no such Category: "'.$Category_Slug.'".</h3>';
				require $Footer;
			} else {
				header('HTTP/1.1 403 Forbidden');
				require $Header;
				echo '<h3>There is no such public Category: "'.$Category_Slug.'".</h3>';
				require $Footer;
			}
		} else {

			$Category_Fetch = mysqli_fetch_assoc($Categories);
			$Category_Title = $Category_Fetch['Title'];
			$Category_Description = $Category_Fetch['Description'];

			$TextTitle = $Category_Title;
			$WebTitle = $Category_Title.' &nbsp;&middot;&nbsp; Forum';
			$Canonical = $Canonical.'?category='.$Category_Slug;
			$PostType = 'Forum';
			$FeaturedImage = '';
			$Description = $Category_Description;
			$Keywords = $Category_Title.' category topics forum '.$Category_Description;

			require $Header;

			if($Member_Auth) {
				$Topics = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE NOT `Status`='Hidden' AND `Category`='$Category_Slug' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
			} else {
				$Topics = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE NOT `Status`='Hidden' AND NOT `Status`='Private' AND `Category`='$Category_Slug' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
			}

			if (!$Topics) exit('Invalid Query (Topics): '.mysqli_error($MySQL_Connection));

			$Topics_Count = mysqli_num_rows($Topics);
			if ($Topics_Count == 0) {
				if($Member_Auth) {
					echo '<h3';
					if($Member_Auth) echo ' class="textleft"';
					echo '>There are no Topics in the Category '.$Category_Title;
					if($Member_Auth) echo '<a class="floatright" href="?new">New Topic</a>';
					echo '</h3>';
				} else {
					echo '<h3';
					if($Member_Auth) echo ' class="textleft"';
					echo '>There are no Public Topics in the Category '.$Category_Title;
					if($Member_Auth) echo '<a class="floatright" href="?new">New Topic</a>';
					echo '</h3>';
				}
			} else {

				require $Parsedown;

				echo '
				<h2>'.$Category_Title.'</h2>
				<p>'.$Category_Description;
				if($Member_Auth) echo '<a class="floatright" href="?new">New Topic</a>';
				echo '</p>';

				echo '
				<div class="section group darkrow">
					<div class="col span_1_of_12"><br></div>
					<div class="col span_7_of_12"><p>Topic</p></div>
					<div class="col span_2_of_12 textcenter faded"><p>Replies</p></div>
					<div class="col span_2_of_12 textcenter faded"><p>Posted</p></div>
				</div>';

				while($Topics_Fetch = mysqli_fetch_assoc($Topics)) {

					$Topics_Slug = $Topics_Fetch['Slug'];
					$Topics_Status = $Topics_Fetch['Status'];
					$Topics_Title = html_entity_decode($Topics_Fetch['Title'], ENT_QUOTES, 'UTF-8');
					$Topics_Created = date('d M, Y', $Topics_Fetch['Created']);
					$Topics_Modified = $Topics_Fetch['Modified'];

					if($Topics_Status == 'Public') {
						echo '
				<a href="?topic='.$Topics_Slug.'" class="section group topic">
					<div class="col span_1_of_12"><li class="icon unread"></li></div>
					<div class="col span_7_of_12"><p class="title">'.$Topics_Title.'</p></div>
					<div class="col span_2_of_12 textcenter"><p><span>14<span></p></div>
					<div class="col span_2_of_12 textcenter"><p><span>'.$Topics_Created.'</span></p></div>
				</a>'; // TODO Unread/Read, Reply Count

					} else if($Topics_Status == 'Private' && $Member_Auth) {
						echo '
				<a href="?topic='.$Topics_Slug.'" class="section group topic private">
					<div class="col span_1_of_12"><li class="icon unread"></li></div>
					<div class="col span_7_of_12"><p class="title">'.$Topics_Title.'</p></div>
					<div class="col span_2_of_12 textcenter"><p><span>14<span></p></div>
					<div class="col span_2_of_12 textcenter"><p><span>'.$Topics_Created.'</span></p></div>
				</a>'; // TODO Unread/Read, Reply Count
					}

				}
			}

			require $Footer;

		}

	} else if(isset($_GET['new'])) {
		require $Header;

		echo '
				<h2>Post a new Topic</h2>
				<form action="" method="post">
					<input type="hidden" name="action" value="topic" required />
					<input type="hidden" name="category" value="Plans" required />
					<input type="text" name="title" value="" placeholder="What is a Forum?" required />
					<textarea name="post" placeholder="This bit is optional, but guarantees you get first post."></textarea>
					<div class="section group">
						<div class="col span_10_of_12">
							<p><small>If you wish, you can use Markdown for formatting.<br>
							Markdown can be used to make [<a href="#">links</a>](http://example.com),<br>
							<strong>**bold text**</strong>, <em>_italics_</em> and <code>`code`</code>.</small></p>
						</div>
						<div class="col span_2_of_12">
							<input type="submit" value="Create">
						</div>
					</div>
				</form>';

		require $Footer;

	} else {

		require $Header;

		echo '
		<h2>Forum</h2>';

		if($Member_Auth) {
			$Categories = mysqli_query($MySQL_Connection, "SELECT * FROM `Categories` WHERE NOT `Status`='Hidden' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
		} else {
			$Categories = mysqli_query($MySQL_Connection, "SELECT * FROM `Categories` WHERE NOT `Status`='Hidden' AND NOT `Status`='Private' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
		}

		if (!$Categories) exit('Invalid Query (Categories): '.mysqli_error($MySQL_Connection));

		$Categories_Count = mysqli_num_rows($Categories);
		if ($Categories_Count == 0) {
			if($Member_Auth) {
    			header('HTTP/1.1 404 Not Found');
				echo '
				<h3>There are no Categories.</h3>';
			} else {
				echo '
				<h3>There are no public Categories.</h3>';
			}
		} else {

			echo '
				<div class="section group darkrow">
					<div class="col span_1_of_12"><br></div>
					<div class="col span_7_of_12"><p>Category</p></div>
					<div class="col span_2_of_12 textcenter faded"><p>Topics</p></div>
					<div class="col span_2_of_12 textcenter faded"><p>Most Recent</p></div>
				</div>';

			if($Member_Auth) {
				$Topic_Count = mysqli_query($MySQL_Connection, "SELECT `Category`, COUNT(*) AS `Count` FROM `Topics` WHERE NOT `Status`='Hidden' GROUP BY `Category`", MYSQLI_STORE_RESULT);
			} else {
				$Topic_Count = mysqli_query($MySQL_Connection, "SELECT `Category`, COUNT(*) AS `Count` FROM `Topics` WHERE NOT `Status`='Hidden' AND NOT `Status`='Private' GROUP BY `Category`", MYSQLI_STORE_RESULT);
			}
			if (!$Topic_Count) exit('Invalid Query (Topic_Count): '.mysqli_error($MySQL_Connection));
			$Topic_Counts = array();

			while($Topic_Count_Fetch = mysqli_fetch_assoc($Topic_Count)) {
				$Topic_Counts[$Topic_Count_Fetch['Category']] = $Topic_Count_Fetch['Count'];
			}

			while($Category_Fetch = mysqli_fetch_assoc($Categories)) {

				$Category_Slug = $Category_Fetch['Slug'];
				$Category_Title = html_entity_decode($Category_Fetch['Title'], ENT_QUOTES, 'UTF-8');
				$Category_Description = html_entity_decode($Category_Fetch['Description'], ENT_QUOTES, 'UTF-8');
				$Category_Status = $Category_Fetch['Status'];

				if($Category_Status == 'Public'||$Category_Status == 'Private' && $Member_Auth) {
					echo '
				<a href="?category='.$Category_Slug.'" class="section group category topic';
					if($Category_Status == 'Private') echo ' private';
					echo '">
					<div class="col span_1_of_12"><li class="icon unread"></li></div>
					<div class="col span_7_of_12">
						<p class="title">'.$Category_Title.'</p>
						<p>'.$Category_Description.'</p>
					</div>
					<div class="col span_2_of_12 textcenter"><p><span>';
					if(isset($Topic_Counts[$Category_Slug])) {
						echo $Topic_Counts[$Category_Slug];
					} else {
						echo '0';
					}
					echo '<span></p></div>
					<div class="col span_2_of_12 textcenter"><p><span>11 Dec, 2014</span></p></div>
				</a>'; // TODO Unread/Read, Most Recent
				}
			}
		}

		require $Footer;

	}

}
