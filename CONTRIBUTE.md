### Code Style

* Tabs, not spaces.
* Blank lines should not include whitespace.
* Function Globals should be in Alphabetical Order, and only include variables that may be used.
* `if`s should be commented at open and close, as below.

```
function Object_ActionFoo( $Hello, $World ) {
	
	global $Connection, $Stuff;
	
	$Array = array();
	
	// This if does a thing
	if ( !empty($Hello) ) $Array['Hello'] = $Hello;
		
	// Else do another thing
	else $Array['Hello'] = 'Hello';
	
	// IF WORLD: What this if does
	if ( !empty($World) ) {
		$Array['World'] = $World;
		if ( $Sitewide_Debug && $World = 'World' ) echo '<p>Debug: You do not need to set \'$World\' as \'World\'.</p>';
	
	// or do the thing
	} else DoThing();
	// END IF WORLD
	
	return $Array;
	
}
```
