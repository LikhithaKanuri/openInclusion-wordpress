<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Tsas_Script {

	function __construct() {

		// Action to add style at admin side
		add_action( 'admin_enqueue_scripts', array( $this, 'tsas_admin_style_script' ) );

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'tsas_front_style') );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'tsas_front_script') );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @package WP Team Showcase and Slider
	 * @since 2.4
	 */
	function tsas_register_admin_assets() {

		// Registring and enqueing admin css
		wp_register_style( 'tsas-admin-css', WP_TSAS_URL.'assets/css/wp-tsas-admin.css', array(), WP_TSAS_VERSION );

		// Registring admin script
		wp_register_script( 'tsas-admin-js', WP_TSAS_URL.'assets/js/tsas-admin.js', array('jquery'), WP_TSAS_VERSION, true );
	}

	/**
	 * Function to add script at admin side
	 * 
	 * @package WP Team Showcase and Slider
	 * @since 2.3
	 */
	function tsas_admin_style_script( $hook ) {

		global $typenow;

		$this->tsas_register_admin_assets();

		// Taking pages array
		$pages_arr = array( WP_TSAS_POST_TYPE );

		if( in_array($typenow, $pages_arr) ) {
			wp_enqueue_style( 'tsas-admin-css' );
		}

		// How it work page
		if( $hook == WP_TSAS_POST_TYPE.'_page_tsas-designs' ) {
			wp_enqueue_script( 'tsas-admin-js' );
		}
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package WP Team Showcase and Slider
	 * @since 1.0.0
	 */
	function tsas_front_style() {

		wp_register_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), WP_TSAS_VERSION );
		wp_enqueue_style( 'fontawesome' );

		// Registring and enqueing slick slider css
		if( ! wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WP_TSAS_URL.'assets/css/slick.css', array(), WP_TSAS_VERSION );
			wp_enqueue_style( 'wpos-slick-style' );
		}

		// Registring and enqueing wpos-magnific-popup-style css
		if( ! wp_style_is( 'wpos-magnific-popup-style', 'registered' ) ) {
			wp_register_style( 'wpos-magnific-popup-style', WP_TSAS_URL.'assets/css/magnific-popup.css', array(), WP_TSAS_VERSION );
			wp_enqueue_style( 'wpos-magnific-popup-style' );
		}

		// Registring and enqueing public css
		wp_register_style( 'tsas-public-style', WP_TSAS_URL.'assets/css/wp-tsas-public.css', array(), WP_TSAS_VERSION );
		wp_enqueue_style( 'tsas-public-style' );
	}

	/**
	 * Function to add script at front side
	 * 
	 * @package WP Team Showcase and Slider
	 * @since 1.0.0
	 */
	function tsas_front_script() {
		
		global $post;

		// Registring slick slider script
		if( ! wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WP_TSAS_URL.'assets/js/slick.min.js', array('jquery'), WP_TSAS_VERSION, true );
		}

		// Registring slick slider script
		if( ! wp_script_is( 'wpos-magnific-popup-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-magnific-popup-jquery', WP_TSAS_URL.'assets/js/jquery.magnific-popup.min.js', array('jquery'), WP_TSAS_VERSION, true );
		}

		// Register Elementor script
		wp_register_script( 'tsas-elementor-js', WP_TSAS_URL.'assets/js/elementor/tsas-elementor.js', array('jquery'), WP_TSAS_VERSION, true );

		// Registring and enqueing public script
		wp_register_script( 'tsas-public-script', WP_TSAS_URL.'assets/js/tsas-public.js', array('jquery'), WP_TSAS_VERSION, true );
		wp_localize_script( 'tsas-public-script', 'WpTsas', array(
															'is_avada' 	=> (class_exists( 'FusionBuilder' ))	? 1 : 0,
														));

		// Enqueue Script for Elementor Preview
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wpos-magnific-popup-jquery' );
			wp_enqueue_script( 'tsas-public-script' );
			wp_enqueue_script( 'tsas-elementor-js' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->tsas_register_admin_assets();

			wp_enqueue_script( 'tsas-admin-js' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wpos-magnific-popup-jquery' );
			wp_enqueue_script( 'tsas-public-script' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->tsas_register_admin_assets();

			wp_enqueue_style( 'tsas-admin-css');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->tsas_register_admin_assets();

			wp_enqueue_style( 'tsas-admin-css');
		}
	}
}

$tsas_script = new Tsas_Script();