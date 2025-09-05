<nav id="main-nav" role="navigation" aria-label="Main navigation">
<!-- <h2 class="screen-reader-text">Main Navigation</h2> -->
<?php
$menuArgs = array( 
   'theme_location' => 'primary',
   'menu_id' => 'nav',
   'link_before' => '<span class="nav-before"></span><span class="nav-text">',
   'link_after' => '</span><span class="nav-after"></span>',
);

 wp_nav_menu($menuArgs); ?>
</nav><!-- End of main-nav -->