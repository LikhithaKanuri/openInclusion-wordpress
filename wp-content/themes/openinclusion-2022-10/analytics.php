<!-- Begin Google Analytics -->
<?php
//find out if user is logged and is an adminstrator
global $user_ID;
$logged_in = false;
if( $user_ID ) {
	 if( current_user_can('level_10') ) {
	 	$logged_in = true;
	 }
}
?>
<?php

//hide GA if admin is logged in
if (!$logged_in) {


?>
<script type="text/javascript">
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','__gaTracker');

	__gaTracker('create', 'UA-102877372-1', 'auto');
	__gaTracker('set', 'forceSSL', true);
	__gaTracker('send','pageview');

</script>
<?php
} // End of if logged in

if (is_front_page()) {
	echo '<meta name="google-site-verification" content="ZOcLzVU27uNq_MepBC59Ubf1zswP76M5wW9m4Hv8zbQ" />';
}

?>	
<!-- End Google Analytics -->