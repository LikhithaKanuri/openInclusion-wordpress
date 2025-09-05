<?php
if (have_posts()) : while (have_posts()) : the_post(); 
?>
<article>
	<div class="wrapper <?php echo (is_front_page()) ? "green-dark":"white"; ?>" id="header-wrapper">
		<header class="container">
	   		<h1><?php the_title(); ?></h1>
		</header>
	</div>
	<?php
	$_a7_sub_heading = get_post_meta(get_the_ID(), '_a7_sub_heading', true);
	if ($_a7_sub_heading) {?>
		<div class="wrapper white" id="sub-heading-wrapper">
			<div class="container">
				<h2 class="sub-heading"><?php echo $_a7_sub_heading ?></h2>
			</div>
		</div>
	<?php } ?>
<?php 
//the_content(); 
?>
<?php 
// ACF Fields
// check if the flexible content field has rows of data
get_template_part( 'acf-page-loop' ); 
?>
<?php 
endwhile; 
?>
<?php 
else : 
?>

<h3>Not Found</h3>
<p>Sorry, but you are looking for something that isn't here.</p>
<?php 
endif; 
?>
</article>