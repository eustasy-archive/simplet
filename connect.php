<?php

if(isset($Database_Host) &&  isset($Database_User) &&  isset($Database_Pass) &&  isset($Database_Database)) {
	$MySQL_Connection = mysqli_connect($Database_Host, $Database_User, $Database_Pass, $Database_Database);
	if (!$MySQL_Connection) exit('MySQL Connection Error: ' . mysqli_connect_error($MySQL_Connection));
} else {
	exit('Simplet Error: No Database Configuration.');
}

?>
