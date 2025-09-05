<div class="wrapper grey-dark footer">
	<footer id="bottom" class="container" aria-labelledby="footer-header">
		<h2 class="srdr" id="footer-header">Extra information and navigation</h2>
		<div class="footer-row">
			<div class="footer-column" id="footer-search">	
				<?php dynamic_sidebar( 'footer-1' ); ?>
         	</div>
         	<div class="footer-column" id="footer-social">
	         	<?php dynamic_sidebar( 'footer-2' ); ?>
	        </div>	
      	</div>
	  	<div class="footer-row">
			<div class="footer-column" id="footer-enquiries">
				<?php dynamic_sidebar( 'footer-3' ); ?>
            </div>	
			<div class="footer-column" id="footer-address">
				<?php dynamic_sidebar( 'footer-4' ); ?>
            </div>	
			<div class="footer-column" id="footer-tel">
				<?php dynamic_sidebar( 'footer-5' ); ?>
            </div>	
        </div>
	  	<div id="copyright">
				<?php dynamic_sidebar( 'footer-6' ); ?>
        </div>	

	</footer>
</div><!-- End of wrapper -->
<div id="back-to-top">
	<a href="#top">
		<svg viewBox="0 0 36 36" class="icon icon-button-top">
			<use xlink:href="#icon-button-top"></use>
		</svg>
		<span aria-hidden="true">Top</span>
	</a>
</div>
<?php	wp_footer(); ?>

