<?php
/**
 * `wp-team` Shortcode
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function get_wp_tsas_showcase( $atts, $content = null ) {

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ($_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json') ) {
		return "[wp-team]";
	}

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="tsas-builder-shrt-prev">
					<div class="tsas-builder-shrt-title"><span>'.esc_html__('Team Showcase Grid View - Shortcode', 'wp-team-showcase-and-slider').'</span></div>
					wp-team
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="tsas-builder-shrt-prev">
					<div class="tsas-builder-shrt-title"><span>'.esc_html__('Team Showcase Grid View - Shortcode', 'wp-team-showcase-and-slider').'</span></div>
					wp-team
				</div>';
	}

	// Global Some Variables
	global $post;

	// setup the query
	extract(shortcode_atts(array(
		'limit'			=> -1,
		'category'		=> '',
		'design'		=> 'design-1',
		'grid'			=> 3,
		'popup'			=> 'true',
		'order'			=> 'DESC',
		'orderby'		=> 'date',
		'extra_class'	=> '',
		'className'		=> '',
		'align'			=> '',
	), $atts, 'wp-team'));

	$shortcode_designs 	= tsas_designs();
	$limit			= ! empty( $limit )					? $limit						: -1;
	$category		= ! empty( $category )				? explode(',', $category)		: '';
	$design			= ( $design && ( array_key_exists( trim( $design ), $shortcode_designs ) ) ) ? trim( $design ) : 'design-1';
	$gridcol		= ! empty( $grid )					? $grid							: 3;
	$order			= ( strtolower( $order ) == 'asc' ) ? 'ASC'							: 'DESC';
	$orderby		= ! empty( $orderby )				? $orderby						: 'date';
	$teampopup		= ( $popup == 'false' )				? 'false'						: 'true';
	$popup_class	= ( $teampopup == "true" )			? 'tsas-enable-popup'			: '';
	$align			= ! empty( $align )					? 'align'.$align				: '';
	$extra_class	= $extra_class .' '. $align .' '. $className;
	$extra_class	= wp_tsas_sanitize_html_classes( $extra_class );
	$lazyload		= '';

	// Popup Configuration
	$popup_id	= wp_tsas_get_unique();
	$popup_conf	= compact('teampopup');
	$per_row	= wp_tsas_grid_column( $gridcol );

	// Shortcode design file
	$design_file_path	= WP_TSAS_DIR . '/templates/designs/' . $design . '.php';
	$design_file		= (file_exists($design_file_path)) ? $design_file_path : '';

	/***** Enqueus Required Script *****/
	// First Dequeue if grid shortcode is placed before the slider shortcode
	wp_dequeue_script( 'tsas-public-script' );
	wp_enqueue_script( 'wpos-magnific-popup-jquery' );
	wp_enqueue_script( 'tsas-public-script' );

	// Count Variable
	$count	= 0;
	$i		= 1;

	ob_start();

	//defualt variable 
	$post_type = 'team_showcase_post';

	// argument wp query
	$args = array ( 
				'post_type'			=> $post_type,
				'orderby'			=> $orderby,
				'order'				=> $order,
				'posts_per_page'	=> $limit,
	);

	if( $category != "" ){
		$args['tax_query'] = array( array( 'taxonomy' => 'tsas-category', 'field' => 'term_id', 'terms' => $category) );
    }

    // Wp Query
	$query = new WP_Query( $args );
	$post_count	= $query->post_count;
	
	if ( $query->have_posts() ) { ?> 

		<div class="wp-tsas-wrp <?php echo esc_attr($popup_class); ?> <?php echo esc_attr($extra_class); ?>" id="tsas-wrp-<?php echo esc_attr($popup_id); ?>">
	  		<div class="wp_teamshowcase_grid <?php echo esc_attr($design); ?>">
				<?php
					while ( $query->have_posts() ) : $query->the_post();
						
						$count++;
						$feat_image			= wp_get_attachment_url( get_post_thumbnail_id() );
						$member_designation	= get_post_meta( $post->ID, '_member_designation', true );
						$member_department	= get_post_meta( $post->ID, '_member_department', true );
						$skills				= get_post_meta( $post->ID, '_skills', true );
						$member_experience	= get_post_meta( $post->ID, '_member_experience', true );
						$facebook_link		= get_post_meta( $post->ID, '_facebook_link', true );
						$google_link		= get_post_meta( $post->ID, '_google_link', true ); 
						$likdin_link		= get_post_meta( $post->ID, '_likdin_link', true );
						$twitter_link		= get_post_meta( $post->ID, '_twitter_link', true );

						// first class and last class
						$css_class	="team-grid";
						if( $count % $grid == 1 ){
							$css_class .= ' tsas-first';
						} elseif ( $count == $grid ) {
							$count = 0;
							$css_class .= ' tsas-last';
						}

						$class	= 'wp-tsas-medium-'.$per_row.' wp-tsas-columns';

						// Include shortcode html file
						if( $design_file ) {
							include( $design_file );
						}

						//Popup file  
						if( $teampopup == "true" ) {
							include(WP_TSAS_DIR. '/templates/popup/popup-design-1.php');
						}
						$i++;
					endwhile;
				?>
			</div>
		</div>
	<?php }
	wp_reset_postdata();
	return ob_get_clean();
}
// grid shortcode action
add_shortcode('wp-team','get_wp_tsas_showcase');