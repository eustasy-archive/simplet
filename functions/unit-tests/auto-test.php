<!DocType>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Simplet Unit-Test Auto-Tester</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/g/normalize,colors.css">
		<style>
		body {
			max-width: 800px;
			margin: 0 auto;
		}
		code {
			white-space: pre-wrap;
			/* Be VERY careful with this, breaks normal words wh_erever */
			word-break: break-all;
			/* Non standard for webkit */
			word-break: break-word;
		}
		h1,
		h2 {
			font-weight: normal;
		}
		header {
			margin: 100px 0;
		}
		table {
			width: 100%;
		}
		td {
			padding: 10px 0;
		}
		.align-center {
			text-align: center;
		}
		.align-left {
			text-align: left;
		}
		.align-right {
			text-align: right;
		}
		.display-none {
			display: none;
		}
		.pad-10 {
			padding: 10px;
		}
		/*
			.top {
				font-weight: bold;
			}
		*/
		</style>
		<script src="https://cdn.jsdelivr.net/g/jquery"></script>
	</head>
	<body>
		<header>
			<h1 class="align-center">Simplet Unit-Test Auto-Tester</h1>
			<h2 class="js-target-count-success align-center color-asbestos display-none"></h2>
			<h2 class="js-target-count-failures align-center color-asbestos display-none"></h2>
			<h2 class="js-target-count-loadfailures align-center color-asbestos display-none"></h2>
		</header>
		<table>
		</table>
<?php
	// Get a list of the tests.
	$Tests = glob('*.php');
	// Set Blacklist
	$Blacklist = array('auto-test.php', 'initialize.php');
	// Remove blacklisted values.
	foreach ($Blacklist as $Value) {
		if(($Key = array_search($Value, $Tests)) !== false) {
			unset($Tests[$Key]);
		}
	}
?>
		<script>
			$(function() {
				var test;
				<?php
	$Load = 'var load = [';
	// For each test
	foreach ($Tests as $Key => $Value) {
		$Load .= '
					\''.$Value.'\',';
	}
	$Load = rtrim($Load, ',');
	$Load .= '
				];
';
	echo $Load;
	?>
				var length = load.length;
				var success = failures = loadfailures = 0;
				for (var i = 0; i < length; i++) {
					test = load[i];
				// for (test of load) {
					$.getJSON(
						test,
						function( data ) {
							console.log(data.Name + ' ' + data.Status);
							if ( data.Status == 'Success') {
								// Success
								var toAppend = '\
			<tr class="top">\
				<td class="align-left">' + data.Name + '</td>\
				<td class="align-center background-nephritis color-white pad-10">Success</td>\
			</tr>';
								if ( data.Result ) {
									var results = JSON.stringify(data.Result);
									toAppend += '\
			<tr class="background-clouds">\
				<td colspan="2" class="pad-10">\
					<code>' + results + '</code>\
				</td>\
			</tr>';
								}
								// Update Counter
								success += 1;
								if ( success > 1 ) $('.js-target-count-success').text(success + ' Successes');
								else $('.js-target-count-success').text('1 Success').removeClass('display-none');
							} else {
								// Error
								var toAppend = '\
			<tr class="top">\
				<td class="align-left">' + data.Name + '</td>\
				<td class="align-center background-pomegranate color-white pad-10">Failure</td>\
			</tr>';
								if ( data.Errors ) {
									var errors = JSON.stringify(data.Errors);
									toAppend += '\
			<tr class="background-clouds">\
				<td colspan="2" class="pad-10">\
					<code>' + errors + '</code>\
				</td>\
			</tr>';
								}
								// Update Counter
								failures += 1;
								if ( failures > 1 ) $('.js-target-count-failures').text(failures + ' Failures');
								else $('.js-target-count-failures').text('1 failures').removeClass('display-none');
							}
							$('table').append(toAppend);
						}
					).fail(function() {
						// Error
						var toAppend = '\
			<tr class="top">\
				<td class="align-left">' + test + '</td>\
				<td class="align-center background-pomegranate color-white pad-10">Load Failure</td>\
			</tr>';
						$('table').append(toAppend);
						// Update Counter
						loadfailures += 1;
						if ( loadfailures > 1 ) $('.js-target-count-loadfailures').text(loadfailures + ' Load Failures');
						else $('.js-target-count-loadfailures').text('1 Load Failures').removeClass('display-none');
					});
				}
			});
		</script>
	</body>
</html>