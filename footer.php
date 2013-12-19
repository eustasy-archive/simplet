	</div>

	<footer class="group">
		<nav>
			<ul>
				<li><a href="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/legal/website-disclaimer" title="Website Disclaimer">disclaimer</a></li>
				<li><a href="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/legal/copyright-notice" title="Copyright Notice">copyright</a></li>
				<li><a href="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/legal/cookie-policy" title="Cookie Policy">cookies</a></li>
				<li><a href="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/legal/privacy-policy" title="Privacy Policy">privacy</a></li>
				<li><a href="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/legal/terms-of-service" title="Terms Of Service">terms</a></li>
			</ul>
		</nav>
	</footer>

	<!--[if lt IE 9]>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript">window.jQuery || document.write('<script src="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/assets/js/jquery-1.10.2.min.js"><\/script>');</script>
	<![endif]-->
	<!--[if IE 9]><!-->
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/assets/js/jquery-2.0.3.min.js"><\/script>');</script>
	<!--<![endif]-->
	<script src="<?php echo $Request['scheme'].'://'.$Request['host']; ?>/assets/js/jquery.equalize.min.js"></script>
	<script>
		$(function(){equalize()});
		window.onresize=function(){equalize()}
	</script>

</body>
</html>
