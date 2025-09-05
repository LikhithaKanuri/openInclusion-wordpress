<?php 
/* Check if cookiedis is set to 1 as query string in incoming browser request.
   If so, we're wanting to set the cookie in a non javascript environment. So
   call the PHP function to set the cookie and then reload the page so the cookie is picked up. */

if (isset($_GET['cookiedis']) and ($_GET['cookiedis'] == 1)) {
   $cookieTime = 60*60*24*360;
   setcookie("opencookieaccept", 1, time()+$cookieTime,'/');
   
   header('Location: '.get_the_permalink());
   // Make sure that code below does not get executed when we redirect.
   exit;

}
?>
<!DOCTYPE html>
<html lang="en">

<?php get_header(); ?>
<?php 

$thisPageId = get_the_ID();
setMeta();
$bodyClassFrag = (is_front_page())?' home':'';
?>

<body <?php body_class($bodyClassFrag); ?> id="body">
	<div class="loader"></div>
	<?php include_once("svg/svg-defs.svg"); ?>
	<a href="#content" class="access">Skip to Content</a>
      <?php get_template_part( 'cookie-consent' );?>
	<div id="top" class="container"	>
		<?php get_template_part( 'top' );?>
	</div><!-- End of top -->

<?php if(is_front_page() or is_single() or is_archive() or is_tag() or is_category() or is_home()) {?>

<div id="banner" role="banner">
	<img src="/wp-content/themes/openinclusion-2016-01/images/banner-homepage-cropped.jpg" alt="OPEN - Come In. We're OPEN" />
</div>

<?php } ?>

<?php 
// Change page layout based upon whether we're showing News related pages
if (is_home() or is_single() or is_archive() or is_tag() or is_category() or is_singular( 'open_story' )) {


?>
   <div class="two-col-framework">
   <main id="content" class="news" role="main">

   <?php
	   
   if(is_singular( 'open_story' )) {
	 get_template_part( 'content','story' );  
   } elseif(is_single()) {
      get_template_part( 'content','single' ); 
   } elseif(is_home()) {
      get_template_part( 'content','news' ); 
   } else {
      get_template_part( 'content','archive' ); 
   }    ?>
   
   </main><!-- End of content -->

   <?php get_template_part( 'sidebar' );?>
   </div> <!-- end of two-col-framework -->
<?php 
} else {
?>

   <main id="content" role="main" tabindex="-1">
	   
   <?php 
   
   if(is_404()) {
      get_template_part( 'content','404' ); 
   } elseif(is_search()) {
      get_template_part( 'content','search' ); 
   } elseif(getPageName() == 'contact') {
      get_template_part( 'content','contact' ); 
   } else {
      get_template_part( 'content' ); 
   }
   ?>
   
   </main><!-- End of content -->
<?php 
} // End of is it a news related page or not?
?>


<?php get_footer(); ?>


<!-- Status container -->
<div id="status-txt" class="srdr" aria-live="polite"></div>
</body>
</html>




