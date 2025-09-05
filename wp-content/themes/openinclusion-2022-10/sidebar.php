<div id="sidebar-outer" role="complementary">
<h2 class="visuallyhidden">Further Navigation</h2>
<div id="sidebar">
<ul class="widgets">
<?php
if (is_single()) {
	dynamic_sidebar( 'single-post-widget-area' ); 
} else {
	dynamic_sidebar( 'post-widget-area' ); 
}
?>

</ul>

<div class="clear"></div>
</div><!-- End of sidebar -->
</div><!-- End of sidebar-outer -->
