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
		tbody tr:nth-child(odd) {
			background: #fafafa;
		}
		th {
			border-bottom: 3px solid #777;
			font-weight: normal;
		}
		th,
		td {
			padding: 10px;
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
		/*
			.top {
				font-weight: bold;
			}
		*/
		</style>
		<script src="https://cdn.jsdelivr.net/g/jquery,tablesorter"></script>
	</head>
	<body>
		<header>
			<h1 class="align-center">Simplet Unit-Test Auto-Tester</h1>
			<h2 class="js-target-count-success align-center color-asbestos display-none"></h2>
			<h2 class="js-target-count-failures align-center color-asbestos display-none"></h2>
			<h2 class="js-target-count-loadfailures align-center color-asbestos display-none"></h2>
		</header>
		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
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
				var i = 0;
				var success = failures = loadfailures = 0;
				var sorting = [[0,0]];
				$('table').tablesorter({ sortForce: sorting });
				function autoTest() {
					console.log('Loading ' + load[i]);
					$.getJSON(
						load[i],
						function( data ) {
							console.log('Loaded ' + load[i]);
							console.log(data);
							if ( data.Status == 'Success') {
								// Success
								toAppend = '\
			<tr class="top">\
				<td class="align-left">' + data.Name + '</td>\
				<td class="align-center background-nephritis color-white">Success</td>\
			</tr>';
								if ( data.Result ) {
									var results = JSON.stringify(data.Result);
									toAppend += '\
			<tr class="background-clouds">\
				<td colspan="2">\
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
								toAppend = '\
			<tr class="top">';
								if ( data.Name ) toAppend += '\
				<td class="align-left">' + data.Name + '</td>';
								else toAppend += '\
				<td class="align-left">' + load[i] + '</td>';
								toAppend += '\
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
							$('tbody').append(toAppend);
							$('table').trigger('update');
							$('table').trigger('sorton',[sorting]);
							i++;
							if ( i < length ) autoTest();
						}
					).fail(function() {
						// Error
						toAppend = '\
			<tr class="top">\
				<td class="align-left">' + load[i] + '</td>\
				<td class="align-center background-pomegranate color-white pad-10">Load Failure</td>\
			</tr>';
						// Update Counter
						loadfailures += 1;
						if ( loadfailures > 1 ) $('.js-target-count-loadfailures').text(loadfailures + ' Load Failures');
						else $('.js-target-count-loadfailures').text('1 Load Failures').removeClass('display-none');
						$('tbody').append(toAppend);
						$('table').trigger('update');
						$('table').trigger('sorton',[sorting]);
						i++;
						if ( i < length ) autoTest();
					});
				}
				autoTest();
			});
		</script>
	</body>
</html>