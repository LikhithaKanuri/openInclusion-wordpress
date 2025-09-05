<?php

function open_customizer( $wp_customize ) {
	// customizer build code
   
   
   
   

   // Important Page URLs  /////////////////////////////////////////////////////////
   $wp_customize->add_section( 'open_important_urls' , array(
   	'title'      => __( 'Important URLs', 'open2015' ),
   	'priority'   => 200,
   ) );

   
   // Privacy and Cookies page
   // Add settings
   $wp_customize->add_setting( 'open_privacy_cookies_url', array( 
   	'default' => '',
      'sanitize_callback' => 'esc_url_raw'
   ) );
   // Add Controls
   $wp_customize->add_control( 'open_privacy_cookies_url', array(  // setting
   	'label'     => __( 'URL for the Privacy and Cookies page', 'open2015' ),      // label
   	'section'   => 'open_important_urls',               // which section to put it in
   	'type'      => 'textbox',                                   // type of control
   	'priority'  => 10
   ) );
   
   // Remove default customizer sections
   $wp_customize->remove_section( 'static_front_page' );
   
   // Change labels for things
   // $wp_customize->get_section( 'title_tagline' )->title = __( 'Site Title (Logo) & Tagline', 'signpost' );
   

   
}
add_action( 'customize_register', 'open_customizer' );

add_action( 'customize_controls_enqueue_scripts', 'open_customizer_style');
function open_customizer_style() {
    wp_add_inline_style( 'customize-controls', '.wp-full-overlay-sidebar { width: 400px } .wp-full-overlay.expanded { margin-left: 400px } ');
    wp_add_inline_style( 'customize-controls', '.customize-control-textbox input{ width: 100% }  ');
}
