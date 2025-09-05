<head>
	<meta charset="UTF-8" /> 
	<meta name="viewport" content="width=device-width,initial-scale=1">
<!-- 	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> -->
	<title><?php wp_title(''); ?></title>
<!-- <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"> -->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="icon" href="/favicon.ico" type="image/x-icon" /> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" /> 
<!-- <script src="http://restylethis.com/js/md.js"></script> -->
<?php
   // Jquery
   //wp_enqueue_script('jquery');  
   /*  Load this if we ever have dropdown nav
   wp_enqueue_script(
      'tap', 
      get_bloginfo ('template_url').'/js/doubletaptogo.js',
      array( 'jquery' )
   );
   */            
   /*  Javascript to cope with slow loading images 
   wp_enqueue_script(
      'waitforimages', 
      get_bloginfo ('template_url').'/js/jquery.waitforimages.js',
      array( 'jquery' )
   );
   */    
/*
   wp_enqueue_script(
      'open-library', 
      get_bloginfo ('template_url').'/js/library.js',
      array( 'jquery' ),
      false,
      true
   );    
*/        
   
?>


<?php wp_head(); ?>
<?php include_once("analytics.php") ?>
</head>