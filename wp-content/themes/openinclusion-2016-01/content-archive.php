<div class="inner">

<div class="wrapper" id="blog-wrapper">

<!-- Put breadcrumb in here -->
<div id="breadcrumb">
<?php if((!is_front_page()) and function_exists('bcn_display')) { bcn_display();  } ?>
</div>  


<div class="blog-header-text-outer">
<div class="blog-header-text-inner">
   <header class="container">
<?php 
if (is_home()) { /* If this is a category archive */ ?>
   <h1>Open Blog</h1>
<?php  
} elseif (is_category()) { /* If this is a category archive */ ?>
   <h1>Blog posts for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h1>
<?php  
} elseif( is_tag() ) { /* If this is a tag archive */ ?>
   <h1>Blog posts tagged &#8216;<?php single_tag_title(); ?>&#8217;</h1>
<?php 
} elseif (is_day()) { /* If this is a daily archive */ ?>
   <h1>Archive for <?php the_time('F jS, Y'); ?></h1>
<?php 
} elseif (is_month()) { /* If this is a monthly archive */ ?>
   <h1>Archive for <?php the_time('F, Y'); ?></h1>
<?php 
} elseif (is_year()) { /* If this is a yearly archive */ ?>
   <h1>Archive for <?php the_time('Y'); ?></h1>
<?php 
} elseif (is_author()) { /* If this is an author archive */ ?>
   <h1>Author Archive</h1>
<?php /* If this is a paged archive */ 
} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
   <h1>Blog Archives</h1>
<?php
} 
?>

   </header>
</div><!-- End of blog-header-text-inner -->
</div><!-- End of blog-header-text-outer -->




<section class="container clearfix">


<?php 
$post_count = 1;

if (have_posts()) :  
?>
<!-- Now show the blog posts in a grid -->
<div class="blog-posts page<?php if (!is_paged()) echo $post_count; ?>">
<ul>
<?php 


while (have_posts()) : the_post();

  // Get category slug to use as class
  $categories = get_the_category();
  $cat_class = '';

  if ( ! empty( $categories ) ) {
      $cat_class = ( $categories[0]->slug );   
  }

  if ((!is_paged()) && ($post_count == 1)) {
    //echo "First post, first page";

    $imgArgs = array(
       'alt' => '',
       'title'  => '',
       'class' => 'blog-full alignnone'
    );
    // Get the featured image
    $thumb = get_the_post_thumbnail(get_the_ID(),'blog-full',$imgArgs); 

    // Default image
    if (strlen($thumb) < 1) {
       $thumb = '<img src="'.get_bloginfo ('template_url').'/images/open-blog-default-400x267.jpg" width="850" height="375" class="blog-full alignnone" alt="">';
    }
?>
<li class="blog-post-first <?php echo $cat_class; ?>" data-url="<?php the_permalink(); ?>">
  <article>
    <div class="news-img"><?php echo $thumb; ?></div>
    <div class="blog-detail">
      <!-- <div class="news-cat"><?php the_category(', '); ?></div> -->

      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <p class="news-date">By <?php the_author(); ?> | <?php the_time('jS F Y'); ?> </p>
      <?php the_excerpt(); ?>
    </div>
  </article>

</li>

<?php



  } else {





  // This is not the first blog post on the first page
  $imgArgs = array(
   'alt'  => '',
   'title'  => '',
   'class' => 'blog-featured aligncenter'
  );
  // Get the featured image
  $thumb = get_the_post_thumbnail(get_the_ID(),'blog-featured',$imgArgs); 

  // Default image
  if (strlen($thumb) < 1) {
     $thumb = '<img src="'.get_bloginfo ('template_url').'/images/open-blog-default-400x267.jpg" width="250" height="250" class="blog-featured aligncenter" alt="">';
  }
?>

<li class="blog-post <?php echo $cat_class; ?>" data-url="<?php the_permalink(); ?>">
<?php echo $thumb ?>
<div class="blog-detail">
<!-- <p class="category"><?php the_category(', '); ?></p> -->
<h2>
<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h2>
</div>
</li>




<?php 
}



$post_count++;
endwhile; ?>
</ul>
<div class="clear"></div>
</section>
</div><!-- End of blog-posts -->
<?php 


global $wp_query;
// Pagination needs to go in here
if ($wp_query->max_num_pages > 1) {
   $big = 999999999; // need an unlikely integer
   $this_page = max( 1,get_query_var('paged'));

   
   echo '<div class="paginate-links">';
   echo '<h2 class="srdr">More Blog Pages</h2>';
   echo '<p class="paginate-viewing">You are viewing page '.$this_page.' of '.$wp_query->max_num_pages.'.</p>';

   echo paginate_links( array(
   	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
   	'format' => '?paged=%#%',
   	'current' => $this_page ,
   	'total' => $wp_query->max_num_pages,
      'end_size' => 3,
      'type' => 'list',
      'prev_text' => '<span class="button">Back<span class="srdr"> posts</span></span>',
      'next_text' => '<span class="button">Next<span class="srdr"> posts</span></span>',
      'before_page_number' => '<span class="visuallyhidden page">Page </span>',
   	'after_page_number' => ''
   
   ) );
   echo '</div>';
} 

 ?>
</div>
<div class="clear"></div>


<?php else : ?>

<h2 class="noposts">No posts found</h2>
<p>No posts were found that match that category or tag.</p>

<?php endif; ?>
