<?php

////	Time Readable Difference Function
//
// $Difference['Prefered'] =s the difference in time entered in a human readable format.
//
// Time_Readable_Difference(200);

function Time_Readable_Difference($Original) {

	global $Time;

	// IFNOTUNIX If not a unix timestamp, convert it.
	if ( !ctype_digit($Original) ) {
		$Original = strtotime($Original);
	}

	$Difference = array();

	// Figure out the differences.
	$Difference['Seconds'] = $Time - $Original;

	// IFFUTURE If it is positive, it is in the future.
	if ( $Difference['Seconds'] ==  0 ) {
		$Difference['Prefered'] = 'Now';

	} else {

		if ( $Difference['Seconds'] < 0 ) {
			$Difference['Negative'] = true;
			$Difference['Seconds'] = abs($Difference['Seconds']);
		} else {
			$Difference['Negative'] = false;
		}

		$Difference['Minutes'] = floor($Difference['Seconds']/60);
		$Difference['Hours'] = floor($Difference['Minutes']/60);
		$Difference['Days'] = floor($Difference['Hours']/24);
		$Difference['Weeks'] = floor($Difference['Days']/7);
		$Difference['Months'] = floor($Difference['Days']/31);
		$Difference['Years'] = floor($Difference['Days']/365);
		$Difference['Decades'] = floor($Difference['Years']/10);

		if ( $Difference['Decades'] > 1) {
			$Difference['Prefered'] = $Difference['Decades'].' Decades';
		} else if ( $Difference['Decades'] ) {
			$Difference['Prefered'] = $Difference['Decades'].' Decade';
		} else if ( $Difference['Years'] > 1 ) {
			$Difference['Prefered'] = $Difference['Years'].' Years';
		} else if ( $Difference['Years'] ) {
			$Difference['Prefered'] = $Difference['Years'].' Year';
		} else if ( $Difference['Months'] > 1 ) {
			$Difference['Prefered'] = $Difference['Months'].' Months';
		} else if ( $Difference['Months'] ) {
			$Difference['Prefered'] = $Difference['Months'].' Month';
		} else if ( $Difference['Weeks'] > 1 ) {
			$Difference['Prefered'] = $Difference['Weeks'].' Weeks';
		} else if ( $Difference['Weeks'] ) {
			$Difference['Prefered'] = $Difference['Weeks'].' Week';
		} else if ( $Difference['Days'] > 1 ) {
			$Difference['Prefered'] = $Difference['Days'].' Days';
		} else if ( $Difference['Days'] ) {
			$Difference['Prefered'] = $Difference['Days'].' Day';
		} else if ( $Difference['Hours'] > 1 ) {
			$Difference['Prefered'] = $Difference['Hours'].' Hours';
		} else if ( $Difference['Hours'] ) {
			$Difference['Prefered'] = $Difference['Hours'].' Hour';
		} else if ( $Difference['Minutes'] > 1 ) {
			$Difference['Prefered'] = $Difference['Minutes'].' Minutes';
		} else if ( $Difference['Minutes'] ) {
			$Difference['Prefered'] = $Difference['Minutes'].' Minute';
		} else if ( $Difference['Seconds'] ) {
			$Difference['Prefered'] = $Difference['Seconds'].' Second';
		} else if ( $Difference['Seconds'] > 1 ) {
			$Difference['Prefered'] = $Difference['Seconds'].' Seconds';
		}

		if ( $Difference['Negative'] ) {
			$Difference['Prefered'] = 'in '.$Difference['Prefered'];
		} else {
			$Difference['Prefered'] = $Difference['Prefered'].' ago';
		}

	}

	return $Difference;

}