<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/Article">
<head>

	<meta charset="utf-8">
	<meta name="description" content="<?php echo $Description_Plain; ?>">
	<meta name="keywords" content="<?php echo $Keywords . ' ' . $Sitewide_Title; ?>">
	<meta itemprop="name" content="<?php echo $Title_Plain; ?>">
	<meta itemprop="description" content="<?php echo $Description_Plain; ?>">
	<meta itemprop="image" content="<?php echo $Featured_Image; ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="cleartype" content="on">
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">

	<title><?php echo $Title_Plain . ' &nbsp;&middot;&nbsp; ' . $Sitewide_Title; ?></title>

	<link rel="canonical" href="<?php echo $Sitewide_Root.$Canonical; ?>">
	<link rel="icon" href="<?php echo $Sitewide_Root; ?>favicon.ico">
	<link rel="shortcut icon" href="<?php echo $Sitewide_Root; ?>favicon.ico">
	<link rel="stylesheet" media="all" href="//cdn.jsdelivr.net/g/normalize,colors.css">
	<link rel="stylesheet" media="all" href="<?php echo $Sitewide_Root; ?>assets/css/combined.min.css">
	<style>
		.text-left {
			text-align: left;
		}
		.text-center {
			text-align: center;
		}
		.text-right {
			text-align: right;
		}
		.margin-0 {
			margin: 0;
		}
		.likelink {
			display: inline-block !important;
		}
		.likelink input {
			background: transparent !important;
			border: none !important;
			color: #3a8ee6 !important;
			width: 100% !important;
		}
	</style>

	<!-- SelfXSS Protection. -->
	<script>
		console.warn('%cWarning', 'color: #c0392b; font-size: x-large');
		console.warn('%cPasting code here could send your information to bad people. People lie on the internet all the time.', 'color: #c0392b; font-size: large');
	</script>

	<!-- Google Analytics - TODO Turn into plugin. -->
	<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create','UA-45667989-3','eustasy.org');ga('send','pageview');</script>

	<!-- jQl -->
	<script>var jQl={q:[],dq:[],gs:[],ready:function(a){'function'==typeof a&&jQl.q.push(a);return jQl},getScript:function(a,c){jQl.gs.push([a,c])},unq:function(){for(var a=0;a<jQl.q.length;a++)jQl.q[a]();jQl.q=[]},ungs:function(){for(var a=0;a<jQl.gs.length;a++)jQuery.getScript(jQl.gs[a][0],jQl.gs[a][1]);jQl.gs=[]},bId:null,boot:function(a){'undefined'==typeof window.jQuery.fn?jQl.bId||(jQl.bId=setInterval(function(){jQl.boot(a)},25)):(jQl.bId&&clearInterval(jQl.bId),jQl.bId=0,jQl.unqjQdep(),jQl.ungs(),jQuery(jQl.unq()), 'function'==typeof a&&a())},booted:function(){return 0===jQl.bId},loadjQ:function(a,c){setTimeout(function(){var b=document.createElement('script');b.src=a;document.getElementsByTagName('head')[0].appendChild(b)},1);jQl.boot(c)},loadjQdep:function(a){jQl.loadxhr(a,jQl.qdep)},qdep:function(a){a&&('undefined'!==typeof window.jQuery.fn&&!jQl.dq.length?jQl.rs(a):jQl.dq.push(a))},unqjQdep:function(){if('undefined'==typeof window.jQuery.fn)setTimeout(jQl.unqjQdep,50);else{for(var a=0;a<jQl.dq.length;a++)jQl.rs(jQl.dq[a]); jQl.dq=[]}},rs:function(a){var c=document.createElement('script');document.getElementsByTagName('head')[0].appendChild(c);c.text=a},loadxhr:function(a,c){var b;b=jQl.getxo();b.onreadystatechange=function(){4!=b.readyState||200!=b.status||c(b.responseText,a)};try{b.open('GET',a,!0),b.send('')}catch(d){}},getxo:function(){var a=!1;try{a=new XMLHttpRequest}catch(c){for(var b=['MSXML2.XMLHTTP.5.0','MSXML2.XMLHTTP.4.0','MSXML2.XMLHTTP.3.0','MSXML2.XMLHTTP','Microsoft.XMLHTTP'],d=0;d<b.length;++d){try{a= new ActiveXObject(b[d])}catch(e){continue}break}}finally{return a}}};if('undefined'==typeof window.jQuery){var $=jQl.ready,jQuery=$;$.getScript=jQl.getScript};</script>
	<!--[if lt IE 9]>
		<script>jQl.loadjQ('//cdn.jsdelivr.net/g/modernizr,selectivizr,prefixfree,jquery@1.11.0,jquery.equalize,jquery.downboy');</script>
	<![endif]-->
	<!--[if IE 9]><!-->
		<script>jQl.loadjQ('//cdn.jsdelivr.net/g/modernizr,prefixfree,jquery,jquery.equalize,jquery.downboy');</script>
	<!--<![endif]-->
	<script>
		$(function(){
			equalize();
			downBoy();
			window.onresize = function() {
				equalize();
				downBoy();
			}
		})
	</script>

</head>

<body>

	<div id="skiptomain"><p><a href="#content">skip to main content</a></p></div>

	<header class="group">
		<div class="col span_1_of_2">

			<?php
				echo '<h1><a href="'.$Sitewide_Root.'">'.$Sitewide_Title.'</a></h1>';
			?>

		</div>
		<nav class="col span_1_of_2">
			<ul>
				<li><a href="<?php echo $Sitewide_Root; ?>">home</a></li>
				<li><a href="<?php echo $Sitewide_Root; ?>page">page</a></li>
				<li><a href="<?php echo $Sitewide_Root; ?>blog/">blog</a></li>
				<li><a href="<?php echo $Sitewide_Root; ?>contact">contact</a></li>
			</ul>
			<ul>
				<li><a href="<?php echo $Sitewide_Root; ?>forum">forum</a></li>

				<?php
					if (isset($Member_Auth) && $Member_Auth == true) {
						echo '
							<li><a href="'.$Sitewide_Root.'account">account</a></li>
							<form style="display:inline-block;" action="'.$Sitewide_Root.'account?logout" method="POST">
								'.Runonce_CSRF_Form().'
								<li><input style="font:inherit;color:inherit;background:inherit;border:inherit;margin-top:inherit;margin-bottom:inherit;padding:inherit;width:auto;" type="submit" value="logout"></li>
							</form>';
					} else {
						echo '
							<li><a href="'.$Sitewide_Root.'account?login&redirect='.urlencode($Canonical).'">login</a></li>
							<li><a href="'.$Sitewide_Root.'account?register">register</a></li>';
					}
				?>

			</ul>
		</nav>
	</header>

	<?php if ($Canonical != '') echo '<div class="content">';