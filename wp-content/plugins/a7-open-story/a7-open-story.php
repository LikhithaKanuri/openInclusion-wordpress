<?php
/*
Plugin Name: A7 Open Stories
Plugin URI: http://www.atticus7.co.uk/
Description: 
Version: 1.0
Author: Ben Neale
Author URI: http://www.benneale.co.uk/
License: GPLv2
*/

define('A7_PLUGIN_TEMPLATE_VERSION', '1.0');
define('A7_PLUGIN_TEMPLATE_ROOT', plugin_dir_path(__FILE__));
define('A7_PLUGIN_TEMPLATE_URL', plugin_dir_url(__FILE__));


//enqueue scripts
//add_action('admin_enqueue_scripts', 'a7_admin_enqueue');

function a7_open_story_admin_enqueue($hook) {
    if($hook == 'posts_page_a7-template') {
		wp_enqueue_media();
		wp_register_script( 'a7-template', A7_PLUGIN_TEMPLATE_URL . 'script.js', array('jquery'), A7_PLUGIN_TEMPLATE_VERSION );
        wp_enqueue_script( 'a7-template' );
    }
}


function a7_get_open_stories($atts, $content = null) {
	



	// We're outputting a lot of HTML, and the easiest way 
	// to do it is with output buffering from PHP.
	ob_start();
	
	
	
	
	// The Query
	$args = array(
		'post_type' 		=> 'open_story',
		'post_status'		=> 'publish',
		'posts_per_page'	=> -1,
		'orderby'			=> 'ID',
		'order'				=> 'DESC'
	);
	
	$story_query = new WP_Query( $args );
	
	// The Loop
	if ( $story_query->have_posts() ) {?>
		<div id="stories">
		<?php	
		while ( $story_query->have_posts() ) {
			$story_query->the_post(); ?>
			<div class="wrapper purple">
				<section class="story container">
					<div class="story-headline">
							<div class="headline-image-column headline-column">
								<?php the_post_thumbnail('person-grid');?>
								<h4 class="screen-reader-text">Learn more about <?php the_title()?></h4>
								<a href="#<?php //the_permalink()?>" class="button show-story-detail" data-id="<?php echo get_the_ID() ?>" id="show-story-detail-<?php echo get_the_ID() ?>">Learn More</a>
							</div>
							<div class="headline-excerpt-column headline-column">
								<h3><?php the_title()?></h3>
								<?php the_excerpt()?>
							</div>						
					</div>
					<div class="story-detail" id="story-detail-<?php echo get_the_ID() ?>">
						<div class="story-detail-columns">
							<div class="detail-content-column detail-column">
								<blockquote>
									<?php the_content(); ?>
								</blockquote>
							</div>
							<div class="detail-image-column detail-column">
								<?php the_post_thumbnail('person-grid');?>
							</div>
						</div>
					</div>
				</section>
			</div>
			
			
		<?php
		}
		?>
		</div>
		<?php
		/* Restore original Post Data */
		wp_reset_postdata();
	} else {
		//echo 'Nofink!';
	}
	
	
	// Output the content.
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}





add_shortcode("get-stories", "a7_get_open_stories");


function a7_get_open_story($post_id) {
	



	// We're outputting a lot of HTML, and the easiest way 
	// to do it is with output buffering from PHP.
	ob_start();
	
	
	
	
	// The Query
	$args = array(
		'post_type' 		=> 'open_story',
		'post_status'		=> 'publish',
		'posts_per_page'	=> 1,
		'p'				=> $post_id
	);
	
	$story_query = new WP_Query( $args );
	
	//a7_var_dump($story_query);
	
	// The Loop
	if ( $story_query->have_posts() ) {?>
		<?php	
		while ( $story_query->have_posts() ) {
			$story_query->the_post(); ?>
            <div class="wrapper purple story-excerpt-wrapper">
				<section class="container clearfix story-excerpt">
					<div class="column column-image">
						<div class="image-container">
							<?php the_post_thumbnail('person-grid');?>							
						</div>
						<h3><?php the_title()?></h3>
						<h4 class="screen-reader-text">Learn more about <?php the_title()?></h4>
						<a href="#" class="button show-story-detail" data-id="<?php echo get_the_ID() ?>" id="show-story-detail-<?php echo get_the_ID() ?>">Learn More</a>
					</div>
					<div class="column column-text">
						<h3><?php the_title()?></h3>
						<?php the_excerpt()?>
					</div>
            	</section>
            	<section class="container clearfix story-detail" id="story-detail-<?php echo get_the_ID() ?>">
					<div class="story-detail-columns">
						<div class="detail-content-column detail-column">
							<blockquote>
								<?php the_content(); ?>
							</blockquote>
						</div>
						<div class="detail-image-column detail-column">
							<?php the_post_thumbnail('person-grid');?>
						</div>
					</div>	
	            </section>
            </div>

			
			
		<?php
		}
		?>
		<?php
		/* Restore original Post Data */
		wp_reset_postdata();
	} else {
		echo 'Nofink!';
	}
	
	
	// Output the content.
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}

function a7_open_story($post_id) {
	echo a7_get_open_story($post_id);	
}




/* Widget Boilerplate */

//add_action( 'widgets_init', 'a7_register_open_story_widget' );
function a7_register_open_story_widget() {  
    register_widget( 'a7_Plugin_Widget' );  
}  

class a7_Open_Story_Widget extends WP_Widget {
	
	
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'a7_plugin_widget', // Base ID
			__('A7 Plugin Name', 'text_domain'), // Name
			array( 'description' => __( 'Plugin Description', 'plugin_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance['title'] );
		$param = $instance[ 'param' ];

		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		a7_function_name($param);
		echo $args['after_widget'];

	}
	
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Plugin Title', 'plugin_domain' );
		}
		if ( isset( $instance[ 'param' ] ) ) {
			$param = $instance[ 'param' ];
		} else {
			$param = 'post';
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			<label for="<?php echo $this->get_field_id( 'post_types' ); ?>"><?php _e( 'Post Types:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'param' ); ?>" name="<?php echo $this->get_field_name( 'param' ); ?>" type="text" value="<?php echo esc_attr( $param ); ?>" />
	
		</p>
	<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['param'] = ( ! empty( $new_instance['param'] ) ) ? strip_tags( $new_instance['param'] ) : '';
		return $instance;
	}

} // class a7_Plugin_Widget


/* Adding Options */


//add_action( 'admin_init', 'a7_register_plugin_settings' );

function a7_register_open_story_settings() {
	register_setting( 'a7_plugin_options_group', 'a7_option_name' ); 
} 


//add_action('admin_menu', 'a7_options_menu');

function a7_open_story_options_menu() {
	//add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function)
	add_options_page('Page Title', 'Menu Title', 'manage_options', 'a7-options-slug.php', 'a7_options_callback_function');
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	//add_submenu_page( 'edit.php', 'Page Title', 'Menu Title', 'manage_options', 'a7-options-slug.php', 'a7_options_callback_function' ); 
}

function a7_open_story_options_callback_function() {
?>
	<div class="wrap">
		<h2>Page Title</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'a7_plugin_options_group' );?>
			<?php do_settings_sections( 'a7_plugin_options_group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Label</th>
					<td><input type="text" name="a7_option_name" class="large-text" value="<?php echo get_option('a7_option_name'); ?>" /></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>	
	</div>
<?
}


//add_action( 'init', 'a7_custom_custom_taxonomies' );

function a7_open_story_custom_taxonomies()  {
	$labels = array(
	    'name'                       => 'Custom Categories',
	    'singular_name'              => 'Custom Category',
	    'menu_name'                  => 'Custom Categories',
	    'all_items'                  => 'All Custom Categories',
	    'parent_item'                => 'Parent Custom Category',
	    'parent_item_colon'          => 'Parent Custom Category:',
	    'new_item_name'              => 'New Custom Category Name',
	    'add_new_item'               => 'Add New Custom Category',
	    'edit_item'                  => 'Edit Custom Categories',
	    'update_item'                => 'Update Custom Categories',
	    'separate_items_with_commas' => 'Separate Custom Categories with commas',
	    'search_items'               => 'Search Custom Categories',
	    'add_or_remove_items'        => 'Add or remove Custom Categories',
	    'choose_from_most_used'      => 'Choose from the most used Custom Categories',
	);
	$args = array(
	    'labels'                     => $labels,
	    'hierarchical'               => true,
	    'public'                     => true,
	    'show_ui'                    => true,
	    'show_admin_column'          => true,
	    'show_in_nav_menus'          => true,
	    'show_tagcloud'              => false,
	    'args' => array( 'orderby' => 'term_order' ),
	    'rewrite' => array( 'with_front' => false, 'slug' => 'custom-categories' )
	);
	register_taxonomy( 'custom_category', 'cpt', $args );
	register_taxonomy_for_object_type( 'custom_category', 'cpt' );
	

	$labels = array(
	    'name'                       => 'Custom Tags',
	    'singular_name'              => 'Custom Tags',
	    'menu_name'                  => 'Custom Tags',
	    'all_items'                  => 'All Custom Tags',
	    'parent_item'                => null,
	    'parent_item_colon'          => null,
	    'new_item_name'              => 'New Custom Tag Name',
	    'add_new_item'               => 'Add New Custom Tag',
	    'edit_item'                  => 'Edit Custom Tags',
	    'update_item'                => 'Update Custom Tags',
	    'separate_items_with_commas' => 'Separate Custom Tags with commas',
	    'search_items'               => 'Search Custom Tags',
	    'add_or_remove_items'        => 'Add or remove Custom Tags',
	    'choose_from_most_used'      => 'Choose from the most used Custom Tags',
	);
	$args = array(
	    'labels'                     => $labels,
	    'hierarchical'               => false,
	    'public'                     => true,
	    'show_ui'                    => true,
	    'show_admin_column'          => true,
	    'show_in_nav_menus'          => true,
	    'show_tagcloud'              => true,
	    'args' => array( 'orderby' => 'term_order' ),
	    'rewrite' => array( 'with_front' => false, 'slug' => 'custom-tags' )
	);
	register_taxonomy( 'custom_tag', 'Custom', $args );
	register_taxonomy_for_object_type( 'custom_tag', 'cpt' );
}

/*
add_action( 'init', 'a7_create_cpt' );

function a7_create_cpt() {
    register_post_type( 'cpt',
        array(
            'labels' => array(
                'name' => 'CPT',
                'singular_name' => 'CPT',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New CPT',
                'edit' => 'Edit',
                'edit_item' => 'Edit CPT',
                'new_item' => 'New CPT',
                'view' => 'View',
                'view_item' => 'View CPT',
                'search_items' => 'Search CPT',
                'not_found' => 'No CPT found',
                'not_found_in_trash' => 'No CPT found in Trash',
                'parent' => 'Parent CPT'
            ),
 
            'public' => true,
            'menu_position' => 20,
            'supports' => array( 'title', 'editor', 'thumbnail', 'author' ),
            'hierarchical' => true,
            //'taxonomies' => array( 'cpt_category','cpt_tag' ),
            //'menu_icon' => 'dashicons-',
            'has_archive' => true,
            'exclude_from_search' => false,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'rewrite' => array( 'with_front' => false, 'slug' => 'cpt' )
        )
    );
}
*/


add_action( 'init', 'a7_create_open_story' );

function a7_create_open_story() {
	register_post_type( 'open_story',
		array(
			'labels' => array (
				'menu_name' => 'Stories',
				'singular_name' => 'Story',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Story',
				'edit_item' => 'Edit Story',
				'new_item' => 'New Story',
				'view_item' => 'View Story',
				'search_items' => 'Search Stories',
				'not_found' => 'No stories found',
				'not_found_in_trash' => 'No stories found in trash',
				'parent_item_colon' => 'Parent Story',
			),
            'public' => true,
            'menu_position' => 20,
            'supports' => array( 'title', 'editor', 'thumbnail', 'author', 'excerpt'),
            //'taxonomies' => array( 'cpt_category','cpt_tag' ),
            'menu_icon' => 'dashicons-book-alt',
            'has_archive' => false, //was true
            'exclude_from_search' => true, // was false
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'rewrite' => array( 'with_front' => false, 'slug' => 'story' )
			//'supports' => array( 'title', 'editor', 'thumbnail', 'author' ),
			//'description' => '',
			//'use_default_menu_icon' => true,
			//'label' => 'Story',
			//'cctm_show_in_menu' => '1',
			//'cctm_show_in_menu_custom' => '',
			//'show_in_admin_bar' => true,
			//'menu_position' => 20,
			//'rewrite_with_front' => true,
			//'permalink_action' => 'Custom',
			//'rewrite_slug' => 'story',
			//'query_var' => false,
			//'public' => true,
			//'show_ui' => true,
			//'show_in_nav_menus' => true,
			//'publicly_queryable' => true,
			//'include_in_search' => false,
			//'include_in_rss' => false,
			//'capability_type' => 'post',
			//'capabilities' => '',
			//'register_meta_box_cb' => '',
			//'can_export' => true,
			//'cctm_enable_right_now' => '1',
			//'custom_orderby' => '',
			//'custom_order' => 'ASC',
			//'hierarchical' => false,
			//'map_meta_cap' => false,
			//'has_archive' => false,
			//'show_in_menu' => true,
			//'rewrite' => array (
			//	'slug' => 'story',
			//	'with_front' => true,
			//),
			//'is_active' => 1,
			//'custom_fields' =>  array (),
			//'original_post_type_name' => 'open_story',
		)
	);
}


//add_action( 'cmb2_admin_init', 'a7_register_cpt_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function a7_register_open_story_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_a7_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'            => $prefix . 'cpt_metabox',
		'title'         => __( 'CPT Options', 'cmb2' ),
		'object_types'  => array( 'cpt', ), // Post type
		// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$cmb_demo->add_field( array(
		'name'       => __( 'Test Text', 'cmb2' ),
		'desc'       => __( 'field description (optional)', 'cmb2' ),
		'id'         => $prefix . 'text',
		'type'       => 'text',
		//'show_on_cb' => 'yourprefix_hide_if_no_cats', // function should return a bool value
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Text Small', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'textsmall',
		'type' => 'text_small',
		// 'repeatable' => true,
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Text Medium', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'textmedium',
		'type' => 'text_medium',
		// 'repeatable' => true,
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Website URL', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'url',
		'type' => 'text_url',
		// 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
		// 'repeatable' => true,
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Text Email', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'email',
		'type' => 'text_email',
		// 'repeatable' => true,
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Time', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'time',
		'type' => 'text_time',
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Time zone', 'cmb2' ),
		'desc' => __( 'Time zone', 'cmb2' ),
		'id'   => $prefix . 'timezone',
		'type' => 'select_timezone',
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Date Picker', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'textdate',
		'type' => 'text_date',
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Date Picker (UNIX timestamp)', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'textdate_timestamp',
		'type' => 'text_date_timestamp',
		// 'timezone_meta_key' => $prefix . 'timezone', // Optionally make this field honor the timezone selected in the select_timezone specified above
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Date/Time Picker Combo (UNIX timestamp)', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'datetime_timestamp',
		'type' => 'text_datetime_timestamp',
	) );

	// This text_datetime_timestamp_timezone field type
	// is only compatible with PHP versions 5.3 or above.
	// Feel free to uncomment and use if your server meets the requirement
	// $cmb_demo->add_field( array(
	// 	'name' => __( 'Test Date/Time Picker/Time zone Combo (serialized DateTime object)', 'cmb2' ),
	// 	'desc' => __( 'field description (optional)', 'cmb2' ),
	// 	'id'   => $prefix . 'datetime_timestamp_timezone',
	// 	'type' => 'text_datetime_timestamp_timezone',
	// ) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Money', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'textmoney',
		'type' => 'text_money',
		// 'before_field' => '£', // override '$' symbol if needed
		// 'repeatable' => true,
	) );

	$cmb_demo->add_field( array(
		'name'    => __( 'Test Color Picker', 'cmb2' ),
		'desc'    => __( 'field description (optional)', 'cmb2' ),
		'id'      => $prefix . 'colorpicker',
		'type'    => 'colorpicker',
		'default' => '#ffffff',
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Text Area', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'textarea',
		'type' => 'textarea',
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Text Area Small', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'textareasmall',
		'type' => 'textarea_small',
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Text Area for Code', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'textarea_code',
		'type' => 'textarea_code',
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Title Weeeee', 'cmb2' ),
		'desc' => __( 'This is a title description', 'cmb2' ),
		'id'   => $prefix . 'title',
		'type' => 'title',
	) );

	$cmb_demo->add_field( array(
		'name'             => __( 'Test Select', 'cmb2' ),
		'desc'             => __( 'field description (optional)', 'cmb2' ),
		'id'               => $prefix . 'select',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'standard' => __( 'Option One', 'cmb2' ),
			'custom'   => __( 'Option Two', 'cmb2' ),
			'none'     => __( 'Option Three', 'cmb2' ),
		),
	) );

	$cmb_demo->add_field( array(
		'name'             => __( 'Test Radio inline', 'cmb2' ),
		'desc'             => __( 'field description (optional)', 'cmb2' ),
		'id'               => $prefix . 'radio_inline',
		'type'             => 'radio_inline',
		'show_option_none' => 'No Selection',
		'options'          => array(
			'standard' => __( 'Option One', 'cmb2' ),
			'custom'   => __( 'Option Two', 'cmb2' ),
			'none'     => __( 'Option Three', 'cmb2' ),
		),
	) );

	$cmb_demo->add_field( array(
		'name'    => __( 'Test Radio', 'cmb2' ),
		'desc'    => __( 'field description (optional)', 'cmb2' ),
		'id'      => $prefix . 'radio',
		'type'    => 'radio',
		'options' => array(
			'option1' => __( 'Option One', 'cmb2' ),
			'option2' => __( 'Option Two', 'cmb2' ),
			'option3' => __( 'Option Three', 'cmb2' ),
		),
	) );

	$cmb_demo->add_field( array(
		'name'     => __( 'Test Taxonomy Radio', 'cmb2' ),
		'desc'     => __( 'field description (optional)', 'cmb2' ),
		'id'       => $prefix . 'text_taxonomy_radio',
		'type'     => 'taxonomy_radio',
		'taxonomy' => 'category', // Taxonomy Slug
		// 'inline'  => true, // Toggles display to inline
	) );

	$cmb_demo->add_field( array(
		'name'     => __( 'Test Taxonomy Select', 'cmb2' ),
		'desc'     => __( 'field description (optional)', 'cmb2' ),
		'id'       => $prefix . 'taxonomy_select',
		'type'     => 'taxonomy_select',
		'taxonomy' => 'category', // Taxonomy Slug
	) );

	$cmb_demo->add_field( array(
		'name'     => __( 'Test Taxonomy Multi Checkbox', 'cmb2' ),
		'desc'     => __( 'field description (optional)', 'cmb2' ),
		'id'       => $prefix . 'multitaxonomy',
		'type'     => 'taxonomy_multicheck',
		'taxonomy' => 'post_tag', // Taxonomy Slug
		// 'inline'  => true, // Toggles display to inline
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Checkbox', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'checkbox',
		'type' => 'checkbox',
	) );

	$cmb_demo->add_field( array(
		'name'    => __( 'Test Multi Checkbox', 'cmb2' ),
		'desc'    => __( 'field description (optional)', 'cmb2' ),
		'id'      => $prefix . 'multicheckbox',
		'type'    => 'multicheck',
		// 'multiple' => true, // Store values in individual rows
		'options' => array(
			'check1' => __( 'Check One', 'cmb2' ),
			'check2' => __( 'Check Two', 'cmb2' ),
			'check3' => __( 'Check Three', 'cmb2' ),
		),
		// 'inline'  => true, // Toggles display to inline
	) );

	$cmb_demo->add_field( array(
		'name'    => __( 'Test wysiwyg', 'cmb2' ),
		'desc'    => __( 'field description (optional)', 'cmb2' ),
		'id'      => $prefix . 'wysiwyg',
		'type'    => 'wysiwyg',
		'options' => array( 'textarea_rows' => 5, ),
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Test Image', 'cmb2' ),
		'desc' => __( 'Upload an image or enter a URL.', 'cmb2' ),
		'id'   => $prefix . 'image',
		'type' => 'file',
	) );

	$cmb_demo->add_field( array(
		'name'         => __( 'Multiple Files', 'cmb2' ),
		'desc'         => __( 'Upload or add multiple images/attachments.', 'cmb2' ),
		'id'           => $prefix . 'file_list',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'oEmbed', 'cmb2' ),
		'desc' => __( 'Enter a youtube, twitter, or instagram URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'cmb2' ),
		'id'   => $prefix . 'embed',
		'type' => 'oembed',
	) );

	$cmb_demo->add_field( array(
		'name'         => 'Testing Field Parameters',
		'id'           => $prefix . 'parameters',
		'type'         => 'text',
		'before_row'   => 'yourprefix_before_row_if_2', // callback
		'before'       => '<p>Testing <b>"before"</b> parameter</p>',
		'before_field' => '<p>Testing <b>"before_field"</b> parameter</p>',
		'after_field'  => '<p>Testing <b>"after_field"</b> parameter</p>',
		'after'        => '<p>Testing <b>"after"</b> parameter</p>',
		'after_row'    => '<p>Testing <b>"after_row"</b> parameter</p>',
	) );

}
?>