<!-- <div id="banner-inner" role="banner"> -->
<header id="logo">
	<a href="/"><img src="<?php bloginfo ('template_url'); ?>/images/open-logo-dark-135.png" width="135" height="36" alt="Open Inclusion Home Page"></a>
	<!-- <button onclick="MD.toggleToolbar();">Start restylethis</button> -->
</header>
<!-- End of header -->



<!-- <nav id="main-nav" role="navigation" aria-label="Main navigation"> -->
<?php get_template_part( 'navigation' );?>
<!-- </nav>End of main-nav -->


<?php get_template_part( 'accessibility-controls' );?>


<?php get_template_part( 'navigation-responsive' );?>