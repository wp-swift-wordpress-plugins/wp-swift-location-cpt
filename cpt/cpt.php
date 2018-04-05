<?php
function cptui_register_my_cpts_location() {

	/**
	 * Post Type: Locations.
	 */

	$labels = array(
		"name" => __( "Locations", "" ),
		"singular_name" => __( "Locations", "" ),
		"menu_name" => __( "Locations", "" ),
	);

	$args = array(
		"label" => __( "Locations", "" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "location", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-store",
		"supports" => array( "title"),//, "editor", "thumbnail" 
	);

	register_post_type( "location", $args );
}

add_action( 'init', 'cptui_register_my_cpts_location' );