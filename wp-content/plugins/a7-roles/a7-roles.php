<?php
/*
Plugin Name: A7 Roles
Plugin URI: http://www.atticus7.co.uk/
Description: 
Version: 1.0
Author: Ben Neale
Author URI: http://www.benneale.co.uk/
License: GPLv2
*/

define('A7_ROLES_VERSION', '1.0');
define('A7_ROLES_ROOT', plugin_dir_path(__FILE__));
define('A7_ROLES_URL', plugin_dir_url(__FILE__));


//add_action( 'init', 'a7_create_sub_editor_role' );


function a7_roles_activate() {

    // Activation code here...
    a7_create_sub_editor_role();

}

register_activation_hook( __FILE__, 'a7_roles_activate' );


function a7_roles_deactivate() {

    // Activation code here...
    $role_name = 'sub_editor';
	remove_role($role_name);

}

register_deactivation_hook( __FILE__, 'a7_roles_deactivate' );



function a7_create_sub_editor_role() {



	$template_role = get_role('editor');	// Based on Editor		
	$caps = $template_role->capabilities;
	
	//a7_var_dump($caps);
	
	//To start and edit a new blog post (but not to publish)
	// To be able to edit other people's posts that are still in Draft (but not to publish)
	//(For now) To not be able to add, update or publish pages.
	//disable any tweaking of menus, customizer, widgets, and the code editor for the new user level.
	

	//Remove the stuff we don't want in the new role.
	unset($caps['moderate_comments']);
	unset($caps['manage_categories']);
	unset($caps['manage_links']);
	unset($caps['unfiltered_html']);
	unset($caps['edit_published_posts']);
	unset($caps['publish_posts']);
	unset($caps['edit_pages']);
	unset($caps['edit_others_pages']);
	unset($caps['edit_published_pages']);
	unset($caps['publish_pages']);
	unset($caps['delete_pages']);
	unset($caps['delete_others_pages']);
	unset($caps['delete_published_pages']);
	unset($caps['delete_published_posts']);
	unset($caps['delete_private_posts']);
	unset($caps['edit_private_posts']);
	unset($caps['delete_private_pages']);
	unset($caps['edit_private_pages']);
	unset($caps['wpseo_bulk_edit']);


/*
    ALLOWED TO
    upload_files
    edit_posts
    edit_others_posts
	delete_posts
    delete_others_posts
	read_private_posts]
	read_private_pages
*/

	//a7_var_dump($caps);

		
	$role_name = 'sub_editor';
	
	$friendly_role_name = ucwords(str_replace('_', ' ', $role_name));
	
	//a7_var_dump($friendly_role_name);
		
	add_role(
		$role_name,
		$friendly_role_name,
		$caps
	);
	
	$sub_editor = get_role($role_name);
	
	//a7_var_dump($sub_editor);
		
}



?>