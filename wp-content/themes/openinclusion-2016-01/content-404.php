<article>
	<div class="wrapper white">
		<div class="container">
	   		<header>
	   			<h1>Page Not Found</h1>
			</header>			
		</div>
	</div>

    <div class="wrapper yellow-dark">
        <section class="container clearfix 404">
			<p>Here's our sitemap - hopefully you'll find what you are looking for.</p>
			<?php
				$menuArgs = array( 
					'theme_location' => 'sitemap',
					'menu_id' => 'sitemap',
					'echo' => false
				);
				
				echo wp_nav_menu($menuArgs);
			?>
    	</section>
    </div>

 
</article>



