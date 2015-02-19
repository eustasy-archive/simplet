<?php

require_once $Backend['functions'].'input.prepare.php';

// We will need the IP to handle logins, regardless of Cookie Status. Catch it every time.
$User['IP'] = Input_Prepare($_SERVER['REMOTE_ADDR']);