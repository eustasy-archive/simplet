<?php

// TODO Why do these arrays need to be passed?
// Global doesn't seem to allow them

function Pagination_Links($Pagination, $PreserveQueryStrings) {

	$Page_Wayback = $Pagination['Page'] - 2;
	$Page_Previous = $Pagination['Page'] - 1;
	$Page_Next = $Pagination['Page'] + 1;
	$Page_Far = $Pagination['Page'] + 2;

	echo '
	<p class="textcenter">';

	$Paginate_End = '&show='.$Pagination['Show'].$PreserveQueryStrings['Miscellaneous'].$PreserveQueryStrings['Topic'];

	if ($Pagination['Page'] > 3) echo '<span class="floatleft"><a href="?page=1'.$Paginate_End.'">1</a> &emsp; &hellip; &emsp; </span>';

	if ($Pagination['Page'] >= 3) echo '<a href="?page='.$Page_Wayback.$Paginate_End.'">'.$Page_Wayback.'</a> &emsp; ';
	if ($Pagination['Page'] >= 2) echo '<a href="?page='.$Page_Previous.$Paginate_End.'">'.$Page_Previous.'</a> &emsp; ';

	echo $Pagination['Page'];

	if ($Page_Next <= $Pagination['Page Max']) echo ' &emsp; <a href="?page='.$Page_Next.$Paginate_End.'">'.$Page_Next.'</a>';
	if ($Page_Far <= $Pagination['Page Max']) echo ' &emsp; <a href="?page='.$Page_Far.$Paginate_End.'">'.$Page_Far.'</a>';

	if ($Page_Far < $Pagination['Page Max']) echo '<span class="floatright"> &emsp; &hellip; &emsp; <a href="?page='.$Pagination['Page Max'].$Paginate_End.'">'.$Pagination['Page Max'].'</a></span>';

	echo '</p>';

}
