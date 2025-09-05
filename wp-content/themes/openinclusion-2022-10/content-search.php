<article>
	<div class="wrapper white">
		<div class="container">
	   		<header>
	   			<h1 class="show">Search Results for "<?php the_search_query() ?>"</h1>
			</header>			
		</div>
	</div>

    <div class="wrapper yellow-dark">
        <section class="container clearfix 404">
			<p>You searched for <strong>"<?php the_search_query() ?>"</strong> and the search returned <?php echo $wp_query->found_posts; ?> items. Here are the results:</p>

			<?php if (have_posts()) :  ?>

			<?php while (have_posts()) : the_post(); ?>

	<?php
	if (get_post_type() == 'post') {
         $purpose = ' <small>(Blog post)</small>';
         $pType = 'Post';
    } else {
         $purpose = '';
         $pType = 'Page';
    }     
    ?>

		<h2 class="show"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title();  echo $purpose; ?></a></h2>
		
		<?php  if (get_post_type()=='post'){  ?> 			
		<p class="post-header news-date">
		<span class="date">Published: <?php the_time('M jS, Y'); ?> | </span>
		<span class="author">Author: <?php the_author(); ?>
		<br><span class="category">Filed in: <?php the_category(', '); ?></p>				
		<?php  } ?>
		
		<?php the_excerpt(); ?>
		<p class="search-read"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title();  echo ': Read '.$pType.' &raquo'; ?></a></p>
		<?php  if (get_post_type()=='post'){  ?> 			
		
		<?php  } ?>
		<?php endwhile; ?>
		
		<div class="alignleft"><p><?php previous_posts_link('&laquo; Previous page of results'); ?></p></div>
		<div class="alignright"><p><?php next_posts_link('Next page of results &raquo;'); ?></p></div>
		
		<?php else : ?>
		
		<h2>Not Found</h2>
		<p>Sorry, but you are looking for something that isn't here.</p>

		<?php endif; ?>


    	</section>
    </div>

 
<article>
