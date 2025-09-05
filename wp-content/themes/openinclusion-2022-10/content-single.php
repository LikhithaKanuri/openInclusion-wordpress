<?php
// get the featured image if there is one - allocate default if not

$imgArgs = array(
   'alt' => '',
   'title'  => '',
   'class' => 'blog-full alignnone'
);

// Get the image
$thumb = get_the_post_thumbnail(get_the_ID(),'blog-full',$imgArgs); 

if (strlen($thumb) < 1) {
   $thumb = '<img src="'.get_bloginfo ('template_url').'/images/open-blog-default-400x267.jpg" width="850" height="375" class="blog-full alignnone" alt="" >';
}
?>

<article>

<?php
if (have_posts()) : while (have_posts()) : the_post(); 

$categories = get_the_category();
$cat_class = '';

if ( ! empty( $categories ) ) {
    $cat_class = ( $categories[0]->slug );   
}

?>

<!-- Put breadcrumb in here -->


   <div class="wrapper <?php echo $cat_class; ?>" id="blog-wrapper">
<div id="breadcrumb">
<?php if((!is_front_page()) and function_exists('bcn_display')) { bcn_display();  } ?>
</div>  
      <header class="container">
         <div class="news-img"><?php echo $thumb; ?></div>
         <div class="news-cat"><?php the_category(', '); ?></div>

         <h1><?php the_title(); ?></h1>
         <p class="news-date">By <?php the_author(); ?> | <?php the_time('jS F Y'); ?> </p>
      </header>
      <section class="container clearfix">
<?php the_content(); ?>
      </section>
      <footer class="container clearfix">
         <div class="tags-n-socmed">
<?php 
         the_tags( '<div class="tags"><span>Tags: </span><ul class="tagslist"><li>', '</li><li>', '</li></ul></div>' ); 

         echo getSocBookmarks(get_permalink(), get_the_title(), get_the_content());
?>
         </div>
<?php 
         // Setup args for call to random posts generator
         $atts = array (
           "count" => '3', 
         );
         $arr_random_posts = oi_get_random_posts($atts);


         // Having got the array back now present it on the screen - assuming there are some posts to show
         if (count($arr_random_posts) > 0) {
            $str_html = '<div class="blog-posts">';
            $str_html .= '<h2 class="more-hdr">More blog posts like this one:</h2>';
            $str_html .= '<ul>';

            //Process each item in the array
            foreach($arr_random_posts as $random_post) {
               foreach($random_post['category'] as $c){
                  $cat = get_category( $c );
                  $cat_name = $cat->name;
                  $cat_slug = $cat->slug;
               }
               $str_html .= '<li class="blog-post '.$cat_slug.'">';
               $str_html .= $random_post['thumb'];
               $str_html .= '<div class="blog-detail">';
               $str_html .= '<p class="category">'.$cat_name.'</p>';
               $str_html .= '<h2><a href="'.$random_post['permalink'].'">'.$random_post['title'].'</a></h2>';

               $str_html .= '</div></li>';

            }

/*
$post_categories = wp_get_post_categories( $post_id );
$cats = array();
     
foreach($post_categories as $c){
    $cat = get_category( $c );
    $cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
}
*/
            // end div
            $str_html .= '</ul></div>';


            echo $str_html;

         }

?>

      </footer>
   </div>




<div class="inner">





<?php 
endwhile; 
?>

<div class="clear"></div>
</div>
<?php 
else : 
?>

<h2>Not Found</h2>
<p>Sorry, but you are looking for something that isn't here.</p>
<?php 
endif; 
?>
</article>