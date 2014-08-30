<?php

////	Forum Categories Function
//
// Echoes the Forum Categories available to the user.
//
// Forum_Categories();

function Forum_Categories() {
	
	global $Database, $Member_Auth;
	
	// IFEXISTSCATEGORIES
	if ( !$Database['Exists']['Categories'] ) return false;
	else {
		
		////	START Count Query Assembly 
		
		// These will be used again later, as a similar query is run.
		
		// Count Categories 
		$Categories_Query_Select = 'SELECT COUNT(`Slug`) AS `Count` FROM `'.$Database['Prefix'].'Categories` WHERE';
		
		// Limit by Status
		// Allow authenticated users to see Private Categories, but others to only see Public ones.
		if ( $Member_Auth ) $Categories_Query_Status = ' ( `Status`=\'Public\' OR `Status`=\'Private\' )';
		else $Categories_Query_Status = ' `Status`=\'Public\'';
		
		// Order by Creation Time
		$Categories_Query_Order = ' ORDER BY `Created` DESC';
		
		// Assemble Query
		$Categories_Query = $Categories_Query_Select.$Categories_Query_Status.$Categories_Query_Order;
		
		////	END Count Query Assembly
		
		// Run the Query to get a count of available Categories
		$Categories = mysqli_query($Database['Connection'], $Categories_Query, MYSQLI_STORE_RESULT);
		
		// IFCOUNTSUCCESS If the Query was unsuccessful
		if ( !$Categories ) {
			
			// Debug
			if ( $Sitewide_Debug ) echo 'Invalid Query (Categories): '.mysqli_error($Database['Connection']);
			
			// End Function Progress: Critical Failure
			return false;
			
		// IFCOUNTSUCCESS If the Query was successful
		} else {
			
			// Fetch the Count
			$Categories_Fetch = mysqli_fetch_assoc($Categories);
			$Categories_Count = $Categories_Fetch['Count'];
			
			// IFCOUNT If the Count was zero
			if ( $Categories_Count == 0 ) {
				
				// There are no Categories
				if ( $Member_Auth ) echo '
					<h3>There are no Categories.</h3>';
				// There are no public Categories
				else echo '
					<h3>There are no public Categories.</h3>';
				
			// IFCOUNT If the count was not zero
			} else {
				
				////	START Categories DIV
				// Start the section for categories with a header
				echo '
				<div id="categories">
					<div class="section group darkrow">
						<div class="col span_1_of_12"><br></div>
						<div class="col span_7_of_12"><p>Category</p></div>
						<div class="col span_2_of_12 textcenter faded"><p>Topics</p></div>
						<div class="col span_2_of_12 textcenter faded"><p>Most Recent</p></div>
					</div>';
				
				// Prepare Pagination
				$Pagination = Pagination_Pre($Categories_Count);
				
				// Replace Select Query
				$Categories_Query_Select = 'SELECT * FROM `'.$Database['Prefix'].'Categories` WHERE';
				
				// Limit Selection by Page
				if ($Pagination['Page'] === 1) $Categories_Query_Limit = ' LIMIT '.$Pagination['Show'];
				else $Categories_Query_Limit = ' LIMIT '.$Pagination['Start'].', '.$Pagination['Show'];
				
				// Assemble new Query
				$Categories_Query = $Categories_Query_Select.$Categories_Query_Status.$Categories_Query_Order.$Categories_Query_Limit;
				
				// Get Categories
				$Categories = mysqli_query($Database['Connection'], $Categories_Query, MYSQLI_STORE_RESULT);
				
				// IFCATEGORIES Failure
				if ( !$Categories ) {
					
					// Debug
					if ( $Sitwide_Debug ) echo 'Invalid Query (Categories): '.mysqli_error($Database['Connection']);
					
					// End Function Progress: Critical Failure
					return false;
					
				// IFCATEGORIES Success
				} else {
					
					// WHILECATEGORIES
					while ( $Category_Fetch = mysqli_fetch_assoc($Categories) ) {
						
						// Fetch the Category Status
						$Category_Status = $Category_Fetch['Status'];
						
						// TODO Unread/Read, Most Recent
						// Both will probably require changing the topics modified time for every reply.
						// Another option is a new field called something more descriptive like `Last Activity`
						// This should not change the modified time.
						
						// Echo the Link
						echo '
						<a href="?category='.$Category_Fetch['Slug'].'" class="section group category topic';
						
						// Add private as a class if that's its Status
						if ( $Category_Status == 'Private' ) echo ' private';
						
						// Echo the Item
						echo '">
							<div class="col span_1_of_12"><li class="icon unread"></li></div>
							<div class="col span_7_of_12">
								<p class="title">'.$Category_Fetch['Title'].'</p>
								<p>'.$Category_Fetch['Description'].'</p>
							</div>
							<div class="col span_2_of_12 textcenter"><p><span>'.number_format($Category_Fetch['Topics']).'<span></p></div>
							<div class="col span_2_of_12 textcenter"><p><span>'.date('d M, Y', $Category_Fetch['Modified']).'</span></p></div>
						</a>';
						
					} // WHILECATEGORIES
					
					// Preserve Query Strings
					$PreserveQueryStrings = Pagination_PreserveQueryStrings();
					
					// IFPAGINATE Paginate if necessary
					if ( $Pagination['Page Max'] > 1 ) {
						
						// Echo Breaker
						echo '<div class="breaker"></div>';
						
						// Echo Links
						// TODO Why do these arrays need to be passed?
						// Global doesn't seem to allow them
						Pagination_Links($Pagination, $PreserveQueryStrings);
						
						// Echo Breaker
						echo '<div class="breaker"></div>';
						
					} // IFPAGINATE 
					
					// Close the DIV we opened
					echo '
						</div>';
					////	END Categories DIV
					
				} // IFCATEGORIES
				
			} // IFCOUNT
		
		} // IFCOUNTSUCCESS
		
		// Nothing more to do here.
		return true;
		
	} // IFEXISTSCATEGORIES
	
}