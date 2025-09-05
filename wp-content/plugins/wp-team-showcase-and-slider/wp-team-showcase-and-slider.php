<?php
/**
 * Plugin Name: WP Team Showcase and Slider
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/wp-team-showcase-slider/
 * Text Domain: wp-team-showcase-and-slider
 * Domain Path: /languages/
 * Description: Easy to add and display your employees, team members in Grid view, Slider view and in widget. Also work with Gutenberg shortcode block.
 * Author: WP OnlineSupport, Essential Plugin
 * Version: 2.8.3
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/wp-team-showcase-slider/
 *
 * @package WordPress
 * @author WP OnlineSupport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! defined( 'WP_TSAS_VERSION' ) ) {
	define( 'WP_TSAS_VERSION', '2.8.3' ); // Version of plugin
}

if( ! defined( 'WP_TSAS_NAME' ) ) {
	define( 'WP_TSAS_NAME', 'Team Showcase and Slider' ); // Version of plugin
}

if( ! defined( 'WP_TSAS_DIR' ) ) {
	define( 'WP_TSAS_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'WP_TSAS_POST_TYPE' ) ) {
	define('WP_TSAS_POST_TYPE', 'team_showcase_post'); // Plugin post type
}

if( ! defined( 'WP_TSAS_URL' ) ) {
	define( 'WP_TSAS_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

// if( ! defined( 'WP_TSAS_PLUGIN_LINK_UNLOCK' ) ) {
// 	define('WP_TSAS_PLUGIN_LINK_UNLOCK','https://www.essentialplugin.com/essential-plugin-bundle-pricing/?utm_source=WP&utm_medium=Team-Showcase&utm_campaign=Features-PRO'); // Plugin link
// }

// if( ! defined( 'WP_TSAS_PLUGIN_LINK_UPGRADE' ) ) {
// 	define('WP_TSAS_PLUGIN_LINK_UPGRADE','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Team-Showcase&utm_campaign=Upgrade-PRO'); // Plugin Check link
// }


if( ! defined( 'WP_TSAS_PLUGIN_LINK_UPGRADE' ) ) {
	define('WP_TSAS_PLUGIN_LINK_UPGRADE', 'https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Team-Showcase&utm_campaign=Upgrade-PRO'); // Plugin link
}

if( ! defined( 'WP_TSAS_PLUGIN_LINK_UNLOCK' ) ) {
	define('WP_TSAS_PLUGIN_LINK_UNLOCK', 'https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Team-Showcase&utm_campaign=Features-PRO'); // Plugin link
}


if( ! defined( 'WP_TSAS_SITE_LINK' ) ) {
	define('WP_TSAS_SITE_LINK','https://www.essentialplugin.com'); // Plugin link
}

/**
 * Do stuff once all the plugin has been loaded
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */
function wp_tsas_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wp_tsas_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wp_tsas_lang_dir = apply_filters( 'wp_tsas_languages_directory', $wp_tsas_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'wp-team-showcase-and-slider' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'wp-team-showcase-and-slider', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WP_TSAS_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'wp-team-showcase-and-slider', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'wp-team-showcase-and-slider', false, $wp_tsas_lang_dir );
	}
}
add_action('plugins_loaded', 'wp_tsas_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wp_tsas_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wp_tsas_uninstall');

/**
 * Plugin Activation Function
 * Does the initial setup, sets the default values for the plugin options
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */
function wp_tsas_install() {

	wp_tsas_cpt_init();  
    flush_rewrite_rules();

	// Deactivate free version
	if( is_plugin_active('wp-team-showcase-and-slider-pro/wp-team-showcase-and-slider.php') ) {
		add_action('update_option_active_plugins', 'wp_tsas_deactivate_premium_version');
	}
}

/**
 * Deactivate free plugin
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */
function wp_tsas_deactivate_premium_version() {
	deactivate_plugins('wp-team-showcase-and-slider-pro/wp-team-showcase-and-slider.php', true);
}

/**
 * Plugin Deactivation Function
 * Delete  plugin options
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */
function wp_tsas_uninstall() {
	flush_rewrite_rules();
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */
function wp_tsas_admin_notice() {

	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	// Check Lite Version
	$dir	= WP_PLUGIN_DIR . '/wp-team-showcase-and-slider-pro/wp-team-showcase-and-slider.php';

	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link		= add_query_arg( array('message' => 'tsas-plugin-notice'), admin_url('plugins.php') );
	$notice_transient	= get_transient( 'tsas_install_notice' );

	// If free plugin exist
	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
		echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( __('Thank you for activating %s', 'wp-team-showcase-and-slider'), 'WP Team Showcase and Slider').'</strong>.<br/>
					'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'wp-team-showcase-and-slider'), '<strong>(<em>WP Team Showcase and Slider PRO</em>)</strong>' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';      
	}
}

add_action( 'admin_notices', 'wp_tsas_admin_notice');

// Admin file
require_once( WP_TSAS_DIR . '/includes/admin/class-tsas-admin.php' );

// Script Files
require_once( WP_TSAS_DIR . '/includes/class-tsas-script.php' );

// Post Type Files
require_once( WP_TSAS_DIR . '/includes/tsas-post-type.php' );

// Function Files
require_once( WP_TSAS_DIR . '/includes/tsas-functions.php' );

// Shortcode File
require_once( WP_TSAS_DIR . '/includes/shortcodes/tsas-shortcode.php' );
require_once( WP_TSAS_DIR . '/includes/shortcodes/tsas-slider-shortcode.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( WP_TSAS_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

/* Recommended Plugins Starts */
if ( is_admin() ) {
	require_once( WP_TSAS_DIR . '/wpos-plugins/wpos-recommendation.php' );

	wpos_espbw_init_module( array(
							'prefix'	=> 'wp-tsas',
							'menu'		=> 'edit.php?post_type='.WP_TSAS_POST_TYPE,
						));
}
/* Recommended Plugins Ends */

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl26_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id'            => 26,
							'file'          => plugin_basename( __FILE__ ),
							'name'          => 'WP Team Showcase and Slider',
							'slug'          => 'wp-team-showcase-and-slider',
							'type'          => 'plugin',
							'menu'          => 'edit.php?post_type=team_showcase_post',
							'text_domain'   => 'wp-team-showcase-and-slider',
						));

	return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl26_load();
/* Plugin Wpos Analytics Data Ends */