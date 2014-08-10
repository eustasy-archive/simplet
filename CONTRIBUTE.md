### Code Style

```
function Object_ActionFoo( $Hello, $World ) {
	
	global $Connection, $Stuff;
	
	$Array = array();
	
	if ( !empty($Hello) ) $Array['Hello'] = $Hello;
	else $Array['Hello'] = 'Hello';
	
	if ( !empty($World) ) {
		$Array['World'] = $World;
		if ( $Sitewide_Debug && $World = 'World' ) echo '<p>Debug: You do not need to set \'$World\' as \'World\'.</p>';
	}
	
	return $Array;
	
}
```
