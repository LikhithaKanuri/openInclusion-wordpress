<nav id="mobile-nav" aria-label="Main navigation">
	<!-- <h2 class="screen-reader-text">Responsive Navigation</h2> -->
	<input id="show-mobile-nav" type="checkbox">
	<label for="show-mobile-nav">
		<span>Menu</span>
		<svg viewBox="0 0 36 36" class="icon icon-hamburger-menu">
			<use xlink:href="#icon-hamburger-menu"></use>
		</svg>
		<svg viewBox="0 0 36 36" class="icon icon-hamburger-close">
			<use xlink:href="#icon-hamburger-close"></use>
		</svg>
	</label>
	<div id="mobile-nav-drawer">
		<?php //get_template_part( 'accessibility-controls' );?>
<!--
		<form action="/" method="get" class="search-form" id="mobile-search-form">
	        <input type="search" class="text search" name="s" id="mobile-search" value="">
	        <button type="submit">Search</button>
		</form>
-->
		<?php
		$responsive_args = array( 
		   'theme_location' => 'responsive',
		   'menu_id' => 'nav-responsive',
		   'link_before' => '<span class="nav-before"></span><span class="nav-text">',
		   'link_after' => '</span><span class="nav-after"></span>',
		);
		
		 wp_nav_menu($responsive_args); ?>
	</div>
	<div class="mobile-nav-bg"></div>
</nav>