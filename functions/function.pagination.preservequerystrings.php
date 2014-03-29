<?php

function Pagination_PreserveQueryStrings() {

	$PreserveQueryStrings = array();
	$PreserveQueryStrings['Miscellaneous'] = '';
	$PreserveQueryStrings['Page'] = '';
	$PreserveQueryStrings['Show'] = '';
	$PreserveQueryStrings['Topic'] = '';

	if (isset($_GET)) {
		foreach($_GET as $Get_Key => $Get_Value) {
			// Ignore old page and show variables
			if (strtolower($Get_Key) == 'page') {
				$PreserveQueryStrings['Page'] .= '&'.$Get_Key.'='.$Get_Value;
			} else if (strtolower($Get_Key) == 'show') {
				$PreserveQueryStrings['Show'] .= '&'.$Get_Key.'='.$Get_Value;
			} else if (strtolower($Get_Key) == 'topic') {
				// Preserve Topic if Necessary
				if (substr($Get_Value, 0, 1) != '/') $PreserveQueryStrings['Topic'] .= '&'.$Get_Key.'='.$Get_Value;
			} else {
				$PreserveQueryStrings['Miscellaneous'] .= '&'.$Get_Key.'='.$Get_Value;
			}
		}
	}

	return $PreserveQueryStrings;

}
