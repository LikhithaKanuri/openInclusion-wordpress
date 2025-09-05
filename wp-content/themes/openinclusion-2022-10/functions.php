<?php
include("library-functions.php");
include("page-blocks.php");
include("customizer.php");
//include("form-definitions.php");
//include("form-functions.php");
include("reg-panel-new-form-elements.php");
include("reg-panel-new-form-definitions.php");
include("reg-panel-new-form-functions.php");



// Global configuration options
$modeDebug=false;

function debugMode() {
   global $modeDebug;
   return $modeDebug; 
}

function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

//Function to add ACF fields to Relevanssi Search

add_filter('relevanssi_content_to_index', 'a7_acf_custom_fields_to_excerpts', 10, 3); // Added 28/02/18
add_filter('relevanssi_excerpt_content', 'a7_acf_custom_fields_to_excerpts', 10, 3);

function a7_acf_custom_fields_to_excerpts($content, $post, $query=false) {

/*
	$fields = get_field('repeater_field_name', $post->ID);

	if ($fields){
		foreach($fields as $field){
			$content .= " " . $field['repeater_sub_field_1'];
			$content .= " " . $field['repeater_sub_field_2'];
		}
	}
*/

	$fields = get_field('content', $post->ID);

	if ($fields) {
		foreach($fields as $field){
			if($field['acf_fc_layout'] == "intro_text"){
				$content .= " " . $field['intro_text_content'];
			} elseif ($field['acf_fc_layout'] == "full_width_text"){
				$content .= " " . $field['_a7_sub_heading'];
				$content .= " " . $field['full_width_text_content'];
			} elseif ($field['acf_fc_layout'] == "full_bleed_image_block"){
				$content .= " " . $field['_a7_sub_heading'];
				$content .= " " . $field['_a7_image_block_content'];
				$content .= " " . $field['_a7_full_width_content'];
			} elseif ($field['acf_fc_layout'] == "case_study_block"){
				$content .= " " . $field['_a7_client_name'];
				$content .= " " . $field['_a7_case_study_block_content'];
			}
		}
	}
	return $content;
}


// Try to prevent extra paragraphs when processing shortcodes
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99);

// Function to remove empty paragrphs from shortcodes etc
function remove_empty_p($content){
    $content = force_balance_tags($content);
    return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}
add_filter('the_content', 'remove_empty_p', 20, 1);


// Switch on excerpts for pages
//add_post_type_support('page', 'excerpt');

// Custom image sizes
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'person-grid', 400, 400, true ); //(cropped)
	add_image_size( 'blog-featured', 250, 250, true ); //(cropped)
	add_image_size( 'blog-full', 850, 375, true ); //(cropped)
}
// Store page association
$pageName = '';
function getPageName() {
   global $pageName;
   return $pageName; 
}
function setPageName($value) {
   global $pageName;
   $pageName = $value; 
}



//SCRIPT ENQUEUER



add_action( 'wp_enqueue_scripts', 'a7_script_enqueuer' );

function a7_script_enqueuer() {

	
	// FRONT END JS AND CSS ONLY
	if (!is_admin()) {

	 
		wp_enqueue_script('jquery');
		
		wp_enqueue_script(
			'open-library', 
			get_bloginfo ('template_url').'/js/library.js',
			array( 'jquery' ),
			filemtime(get_theme_file_path('/js/library.js')),
			true
		);
    
		wp_localize_script( 'open-library', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    
    
    wp_enqueue_script('modernizr', get_bloginfo ('template_url').'/js/modernizr-custom.min.js', false,  false, true);
    wp_enqueue_script('cookie', get_bloginfo ('template_url').'/js/js.cookie.js', false,  false, true);
    wp_enqueue_script('flyingfocus', get_bloginfo ('template_url').'/js/flying-focus.js', false,  false, true);
			
		
		//Load Google Fonts
		wp_register_style( 'googleFonts', '//fast.fonts.net/cssapi/8a92b6ba-79f9-474a-8f88-a6fe69c2c864.css');
		wp_enqueue_style( 'googleFonts' );
		
		wp_register_style( 'styles', get_bloginfo ('template_url').'/css/styles.css', array(), wp_rand());
		wp_enqueue_style( 'styles' );
		
		
	} 

}



////////////////////////////// Widget areas /////////////////////////////////

function cc_widgets_init() {

  //In the sidebar of news posts.
   register_sidebar( array(
    'name' => __( 'News Widget Area', 'open201601' ),
    'id' => 'post-widget-area',
    'description' => __( 'The news widget area', 'open201601' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );
   

  //In the sidebar of individual blog posts.
   register_sidebar( array(
    'name' => __( 'Single Blog Post Widget Area', 'open201601' ),
    'id' => 'single-post-widget-area',
    'description' => __( 'The individual blog post widget area', 'open201601' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );
   

	//Footer 1
   register_sidebar( array(
		'name' => __( 'Footer 1', 'open201601' ),
		'id' => 'footer-1',
		'description' => __( 'Top Row Left', 'open201601' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
   
	//Footer 2
   register_sidebar( array(
		'name' => __( 'Footer 2', 'open201601' ),
		'id' => 'footer-2',
		'description' => __( 'Top Row Right', 'open201601' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
   
	//Footer 3
   register_sidebar( array(
		'name' => __( 'Footer 3', 'open201601' ),
		'id' => 'footer-3',
		'description' => __( 'Second Row - Left', 'open201601'),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
   
	//Footer 4
   register_sidebar( array(
		'name' => __( 'Footer 4', 'open201601' ),
		'id' => 'footer-4',
		'description' => __( 'Second Row - Centre', 'open201601' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	//Footer 5
   register_sidebar( array(
		'name' => __( 'Footer 5', 'open201601' ),
		'id' => 'footer-5',
		'description' => __( 'Second Row - Right', 'open201601' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	//Footer 6
   register_sidebar( array(
		'name' => __( 'Footer 6', 'open201601' ),
		'id' => 'footer-6',
		'description' => __( 'Third Row - Centre', 'open201601' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title srdr">',
		'after_title' => '</h3>',
	) );

   
}

/** Register sidebars by running widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'cc_widgets_init' );

// Add support for menus
if ( function_exists( 'register_nav_menus' ) ) { 
   register_nav_menus(array('primary'=>__('Primary Menu'),));
   register_nav_menus(array('responsive'=>__('Responsive Menu'),));
   register_nav_menus(array('footernav'=>__('Footer Menu'),));
   register_nav_menus(array('sitemap'=>__('Sitemap Menu'),));
   register_nav_menus(array('categories'=>__('Categories Menu'),));
}


// Remove extra margin from wp-caption
class fixImageMargins{
    public $xs = 0; //change this to change the amount of extra spacing

    public function __construct(){
        add_filter('img_caption_shortcode', array(&$this, 'fixme'), 10, 3);
    }
    public function fixme($x=null, $attr, $content){

        extract(shortcode_atts(array(
                'id'    => '',
                'align'    => 'alignnone',
                'width'    => '',
                'caption' => ''
            ), $attr));

        if ( 1 > (int) $width || empty($caption) ) {
            return $content;
        }

        if ( $id ) $id = 'id="' . $id . '" ';

    return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ((int) $width + $this->xs) . 'px">'
    . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
    }
}
$fixImageMargins = new fixImageMargins();


// Set up page meta
$meta = array();
$pageColour = '';

function getMeta() {
   global $meta;
   return $meta; 
}
function getPageId() {
   global $pageId;
   return $pageId; 
}
function setMeta() {
   global $meta;
   global $pageId;
   $meta = get_post_custom(); 
   
   $pageId = get_the_ID();
}

//////////////////////// Page functionality /////////////////////////


function getTags_sc($atts, $content = null) {
/* Function to output simple tag list */
  $tags = get_tags();
  $strHtml = '<ul class="post-tags">';
  foreach ( $tags as $tag ) {
    $tag_link = get_tag_link( $tag->term_id );
        
    $strHtml .= "<li><a href='{$tag_link}' class='{$tag->slug}'>";
    $strHtml .= "{$tag->name}</a></li>";
  }
  $strHtml .= '</ul>';
  return $strHtml;
}
add_shortcode("get-tags", "getTags_sc");   

function getVideo_sc($atts, $content = null) {
/* Function to output a YouTube video */
   // Get parameters
   extract(shortcode_atts(array(
      'width' => '600', 
      'height' => '375', 
      'type' => 'yt',
      'title' => '',
      'vidid' => '',
   ), $atts));
   
   if (empty($vidid)) return ''; // No ID - no video
   
   // Check that type is either yt or vim (wordpress.tv to follow soon)
   if (($type != 'yt') and ($type != 'vim')) return '';

   // Check if we've been given a title and if so, use it for title attribute for iframe.
   if (empty($title)) {
      $str_title = '';
   } else {
      $str_title = 'title="'.sanitize_text_field($title).' "';
   }

   $strHtml = '<div class="video-block">';
   $strHtml .= '<div class="embed-container">';
   if ($type == 'yt') {
      $strHtml .= '<iframe '.$str_title.'class="video-embed" width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed/'.$vidid.'?rel=0&cc_lang_pref=en&cc_load_policy=1" frameborder="0" allowfullscreen></iframe>';
   }
   if ($type == 'vim') {
      $strHtml .= '
      <iframe '.$str_title.'src="//player.vimeo.com/video/'.$vidid.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
   }
   $strHtml .= '</div></div>';
   return $strHtml;
}
add_shortcode("getvideo", "getVideo_sc");   

///////////////////////// Transcripts for videos ////////////////////////////
function oi_transcriptSC($atts, $content = null) {
// Function to wrap a video transcript in a container. Using CSS the container will initially be partially open.
// Javascript functions will be attached to the buttons to show the full transcript if required.

  // get a unique number to use as the id
  $myId = 't'.uniqid();
   
  // Initialise output
  $strHtml = '';
   
   
   $strHtml .= '<div class="transcript-outer"><div class="transcript-inner" id="'.$myId.'">';
   $strHtml .= '<button id="bs'.$myId.'" data-id="'.$myId.'" class="transhow">Show full transcript</button>';
   $strHtml .= '<button id="bh'.$myId.'" data-id="'.$myId.'" class="tranhide">Hide transcript</button>';
   $strHtml .= $content;
   $strHtml .= '</div></div>';
   return $strHtml;
}
add_shortcode("transcript", "oi_transcriptSC");   

function get_sitemap_sc($atts, $content = null) {

   $menuArgs = array( 
      'theme_location' => 'sitemap',
      'menu_id' => 'sitemap',
      'echo' => false
   
   );
   
   return wp_nav_menu($menuArgs);
   
}
add_shortcode("get-sitemap", "get_sitemap_sc");   


function a7_button($atts, $content = null) {
   extract(shortcode_atts(array('link' => '#','align' => 'left'), $atts));
   return '<div class="button-container button-'.$align.'"><a class="button" href="'.$link.'"><span>' . do_shortcode($content) . '</span></a></div>';
}
add_shortcode('button', 'a7_button');


///////////  Query functions ////////////////

function get_peopleSC($atts=null, $content = null) {
   // Get parameters
   extract(shortcode_atts(array(
      'count' => '-1', // how many to retrieve
      'ids' => '', // specific ids of people
   ), $atts));

   // Set up the array to contain selection options
   $args = array(
      'post_type' => 'open_person',
      'post_status' => 'publish',
      'posts_per_page' => $count,
   );
   
   // Check to see if we're looking for specific people
   if (empty($ids)) {
      // Order by menu
      $args['orderby'] = 'menu_order';
      $args['order'] = 'ASC';
   
   } else {
      // Place items from list in array
      $people = explode(',', $ids);
      
      // Feed into query params
      $args['post__in'] = $people;
      $args['orderby'] = 'post__in';
   } 
   
   
   // Initialise array
   $people = array();
   $myquery0 = new WP_Query($args); // Run the query
   if ($myquery0->have_posts()) :  
      while ($myquery0->have_posts()) : $myquery0->the_post();
         // Store the details
         $imgArgs = array(
            'class'	=> 'person-grid',
            'alt'	=> get_the_title(),
         );
         $imgArgs2 = array(
            'class'	=> 'person-thumb',
            'alt'	=> get_the_title(),
         );
         
         $person = array(
            'id' => get_the_ID(),
            'name' => get_the_title(),
            'excerpt' => get_field('open_person_job_title'),
            'content' => strip_shortcodes(get_the_content()),
            'thumb' => get_the_post_thumbnail(get_the_ID(),'person-grid',$imgArgs),
            'thumb2' => get_the_post_thumbnail(get_the_ID(),'medium',$imgArgs2),
            'location' => get_field('open_person_acf_location'),
            'grouping' => get_field('open_person_grouping')
         );
         
         $people[] = $person;
      endwhile;
   endif;

   wp_reset_query(); // Very important - drops the query and restores where you were
   
   // Check we got some back
   if (count($people) < 1) { return ''; }
   
   // Firstly put out the image blocks
   $strHtml = '<div tabindex="-1" id="team-boxes">'; // Initialise output
   $count = 1;
   foreach($people as $box) {
      // Check grouping - do core team first
      if ($box['grouping'] != 'core') continue;
      
      if (empty($box['thumb'])) {
         // default image in here
      }
      // Start the div
      $cssFrag = '';
      if ($count%2 == 0) $cssFrag .= ' tb2';
      if ($count%3 == 0) $cssFrag .= ' tb3';
      if ($count%4 == 0) $cssFrag .= ' tb4';
      
      

      // Check if there is any content - if so then box will become a link
      // if not the it's just a box
      
      
      if (!empty($box['content'])) {
         $strHtml .= '<div id="team-box-'.$box['id'].'" class="team-box'.$cssFrag.' has-link" tabindex="-1" data-id="'.$box['id'].'">';
         // Put out the thumbnail
         $strHtml .= '<div class="team-img">'.$box['thumb'].'</div>';
         
         // Now the link
      	$strHtml .= '<div class="team-name">';
        $strHtml .= '<a id="team-link-'.$box['id'].'" class="team-link" href="#team-biog-'.$box['id'].'" data-id="'.$box['id'].'" aria-controls="team-biog-'.$box['id'].'" aria-selected="false">'; // put aria in with javascript
      	$strHtml .= '<span class="name">'.$box['name'].'</span><br><span class="role">'.$box['excerpt'].'</span></a>';
        $strHtml .= '</div>'; // end of .team-name
        $strHtml .= '</div>'; // end of .team-box
      
      } else {
         
         $strHtml .= '<div id="team-box-'.$box['id'].'" class="team-box'.$cssFrag.' no-link" >';
         $strHtml .= '<div class="team-img">'.$box['thumb'].'</div>';
         
         // Now the words
         $strHtml .= '<div class="team-no-link">';
         $strHtml .= '<div class="team-name"><span class="name">'.$box['name'].'</span><br><span class="role">'.$box['excerpt'].'</span></div>';
         $strHtml .= '</div>'; // end of .team-no-link
         
         $strHtml .= '</div>'; // end of .team-box
      }
      
      
      /////////////////////// End of new code /////////////////////////////////////////////////////////////////

      
      $count++;
   }
   
   $strHtml .= '<div class="clear"></div>';
   $strHtml .= '</div>'; // End of #team-boxes
   
   // Now put out the biogs
   $strHtml .= '<div id="team-biogs">';
   
   $strHtml .= '<h2 class="srdr">Team biographies</h2>';
   
   
   foreach($people as $box) {
      // Check for description - bale out if there isn't one
      if (empty($box['content'])) continue;
      
      $strHtml .= '<div class="team-biog" id="team-biog-'.$box['id'].'" data-id="'.$box['id'].'" tabindex="-1" aria-labelledby="team-link-'.$box['id'].'">';
   	$strHtml .= '<h3 class="srdr">'.$box['name'].'</h3>';	
      $strHtml .= '<div class="biog-close"><a href="#team-link-'.$box['id'].'" id="biog-close-'.$box['id'].'" data-id="'.$box['id'].'" class="biog-close-link">
      	<svg viewBox="0 0 36 36" class="icon icon-cancel-circle">
			<use xlink:href="#icon-cancel-circle"></use>
		</svg>
      	<span>Close</span>
      	</a>
      </div>';
      
      // Get location and print if available
      if (!empty($box['location'])) {
   	   $strHtml .= '<p class="location">Location: '.$box['location'].'</p>';	
      }
   	$strHtml .= wpautop($box['content']);	

      $strHtml .= '</div>';
      $count++;
   }
   $strHtml .= '</div>';  // End of #team-biogs

   return $strHtml;

}
add_shortcode("get-people", "get_peopleSC");   


/////////////////// Get Random Posts ////////////////////////////////
function oi_get_random_posts($atts) {

  $defaults = array(
      "cat" => '',     // category
      "tag" => '',     // tag
      "count" => '1', // how many to get
      "includeme" => 'n', // show the current post in list?
      
  );

  // Fold the passed values into the defaults
  $atts = wp_parse_args( $atts, $defaults );


  $args=array();
   
  // Check for category and tag
  if (strlen($atts['cat']) > 0){
      $args['category_name'] = $cat;
  } else {
    // No cat selected so try tag - expecting slug
    if (strlen($atts['cat']) > 0){
      // Tag found
      $args['tag'] = $tag;
    }
  }
   
   // Just get one
   $args['posts_per_page'] = $atts['count'];
   
   // set random
   $args['orderby'] = 'rand';
   
   $currId = get_the_ID();
   
   // Check includeme
   if ($atts['includeme'] == 'n') {
      // Exclude this post
      $args['post__not_in'] = array($currId);
   }
   
  // Initialise array to receive data
  $random_post_details = array(); 


  // Run query
  $ccrpQ = new WP_Query($args);
  if ($ccrpQ->have_posts()) :
    // Retrieved some posts

    while ($ccrpQ->have_posts()) : $ccrpQ->the_post();

      // Get featured image - if present
      $imgArgs = array(
        'class'  => "blog-featured aligncenter",
        'alt' => '',
        'title'  => '',
      );
         
      $thumb = get_the_post_thumbnail(get_the_ID(),'blog-featured',$imgArgs);  
         
      if (empty($thumb)) {
        $thumb = '<img src="'.get_bloginfo ('template_url').'/images/open-blog-default.png" width="250" height="260" class="blog-featured aligncenter" alt="">';
      }

      $random_post = array( // Create array to store required details
         'id' => get_the_ID(),
         'title' => get_the_title(),
         'category' => wp_get_post_categories( get_the_ID()),
         'thumb' => $thumb,
         'permalink' => get_permalink(),
         'excerpt' => get_the_excerpt(),
         'date' => get_the_time()
      );

      $random_post_details[] = $random_post;

    endwhile;
   endif;
   wp_reset_query(); 

   return $random_post_details;
}    




// Functionality to deliver one extra post on first blog index page
function tax_and_offset_homepage( $query ) {
  if ($query->is_home() && $query->is_main_query() && !is_admin()) {
    $query->set( 'post_type', 'post' );
    $query->set( 'post_status', 'publish' );
    $query->set( 'ignore_sticky_posts', '-1' );
    /*
    $tax_query = array(
        array(
            'taxonomy' => 'my_taxo',
            'field' => 'slug',
            'terms' => array('slug1', 'slug2', 'slug3')
        )
    );
    $query->set( 'tax_query', $tax_query );
    */
    $ppp = get_option('posts_per_page');
    $offset = 1;
    
    //$query->set('posts_per_page',$ppp);
    //$offset = $offset + ( ($query->query_vars['paged']) * $ppp );
    //$query->set('offset',$offset);

    // Theoretically this should work
    if (!$query->is_paged()) {
      $query->set('posts_per_page',$offset + $ppp);
    } else {
      $offset = $offset + ( ($query->query_vars['paged']-1) * $ppp );
      $query->set('posts_per_page',$ppp);
      $query->set('offset',$offset);
    }
  }
}
add_action('pre_get_posts','tax_and_offset_homepage');

function homepage_offset_pagination( $found_posts, $query ) {
    $offset = 1;

    if( $query->is_home() && $query->is_main_query() ) {
        $found_posts = $found_posts - $offset;
    }
    return $found_posts;
}
add_filter( 'found_posts', 'homepage_offset_pagination', 10, 2 );


//ATTICUS 7 FUNCTIONS

$a7_dump_output = "";

function a7_var_dump($input, $html=1, $die=1, $array=1) {
	global $a7_dump_output;
	$output = "";
	if ($html) {
		if ($array) $output .= '<pre>';
	} else {
		$output .= "\r\n";
	}
	$output .= print_r($input, true);
	if ($html) {
		if ($array) $output .= '</pre>';
	} else {
		$output .= "\r\n";
	}
	$a7_dump_output .= $output;
	if ($die) { 
		wp_die($a7_dump_output);
	}
}


$args = array();

$post_types = get_post_types($args);

//a7_var_dump($post_types);

// Cookie Functions
function check_cookie_cookie() {
   // Check if cookie set
   if (isset($_COOKIE["opencookieaccept"])) {
      // It's set so retrieve choice
      $cookie_accept = trim($_COOKIE["opencookieaccept"]);
   } else {
      $cookie_accept = '';
   }
   return $cookie_accept;
   
} // End of function 

