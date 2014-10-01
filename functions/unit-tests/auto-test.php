<!DocType>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Simplet Unit-Test Auto-Tester</title>
		<script src="https://cdn.jsdelivr.net/g/prefixfree,jquery,tablesorter,jquery.leanmodal"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/g/normalize,colors.css">
		<style>
			body {
				max-width: 800px;
				margin: 0 auto;
				tab-size: 4;
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
			/* External */
			#lean_overlay {
				background: #fff;
				cursor: pointer;
				display: none;
				height:100%;
				left: 0px;
				position: fixed;
				top: 0px;
				width:100%;
				z-index:100;
			}
			.expand {
				cursor: pointer;
			}
			.expandable {
				padding: 20px;
				border: 1px solid #eee;
				border-radius: 5px;
				background: #fafafa;
				max-width: 1000px;
				width: 80%;
				max-height: 60%;
				overflow-y: scroll;
			}
			pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
			.string { color: green; }
			.number { color: darkorange; }
			.boolean { color: blue; }
			.null { color: magenta; }
			.key { color: red; }
		</style>
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
					<th></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<p class="align-center color-asbestos">Functions that echo are not automatically checked. Functions that return should be.</p>
		<script>
			$(function() {
				// Based on code from http://jsfiddle.net/KJQ9K/554/
				function syntaxHighlight(json) {
					json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
					return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
						var cls = 'color-pumpkin'; // Number
						if (/^"/.test(match)) {
							if (/:$/.test(match)) {
								cls = 'color-midnight-blue'; // Key
							} else {
								cls = 'color-nephritis'; // String
							}
						} else if (/true|false/.test(match)) {
							cls = 'color-belize-hole'; // Boolean
						} else if (/null/.test(match)) {
							cls = 'color-alizarin'; // null
						}
						return '<span class="' + cls + '">' + match + '</span>';
					});
				}
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
	$Load = '				var load = [';
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
					var modalAppend = toAppend = false;
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
				<td class="align-center background-nephritis color-white">Success</td>';
								if ( data.Result ) {
									var results = syntaxHighlight(JSON.stringify(data.Result, undefined, '	'));
									toAppend += '\
				<td class="align-center background-belize-hole color-white expand" href="#modal_' + i + '">\
					+\
				</td>';
									modalAppend = '\
				<div id="modal_' + i + '" class="expandable display-none">\
					<code>' + results + '</code>\
				</div>';
								} else toAppend += '\
				<td></td>';
								toAppend += '\
			</tr>';
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
								if ( data.Status ) toAppend += '\
				<td class="align-center background-pomegranate color-white pad-10">' + data.Status + '</td>';
								else toAppend += '\
				<td class="align-center background-pomegranate color-white pad-10">Failure</td>';
								if ( data.Errors.length > 0 ) {
									var errors = syntaxHighlight(JSON.stringify(data.Errors));
									toAppend += '\
				<td class="align-center background-belize-hole color-white expand" href="#modal_' + i + '">\
					+\
				</td>';
									modalAppend = '\
				<div id="modal_' + i + '" class="expandable display-none">\
					<code>' + errors + '</code>\
				</div>';
								} else toAppend += '\
				<td></td>';
								toAppend += '\
			</tr>';
								// Update Counter
								failures += 1;
								if ( failures > 1 ) $('.js-target-count-failures').text(failures + ' Failures');
								else $('.js-target-count-failures').text('1 Failures').removeClass('display-none');
							}
							$('tbody').append(toAppend);
							if ( modalAppend ) $('body').append(modalAppend);
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
					$('.expand').leanModal();
					$('.expand').click(function() {
						console.log('Expand clicked.');
						var modal_id = $(this).attr('href');
						console.log(modal_id);
					});
				}
				autoTest();
			});
		</script>
	</body>
</html>