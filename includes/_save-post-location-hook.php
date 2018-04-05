<?php 
/**
 * The save post hook
 *
 * @author  Gary Swift <gary@brightlight.ie>
 *
 * @since 1.0
 *
 * @param int    $example  This is an example function/method parameter description.
 * @param string $example2 This is a second example. 
 */

function wp_swift_location_save_post($post_id) {
    // Return if this isn't a 'location' post
    if ( "location" != get_post_type($post_id) ) return; 
	add_post_meta_lat_lng($post_id);
}
add_action( 'save_post', 'wp_swift_location_save_post' );
function add_post_meta_lat_lng($post_id) {
    if ( get_field('map', $post_id) ) {
        $map = get_field('map', $post_id); 
        $lat = get_post_meta( $post_id, '_lat', true );
        $lng = get_post_meta( $post_id, '_lng', true );
        if ($map) {
	        if (!$lat && !$lng) {
	            add_post_meta( $post_id, '_lat', $map["lat"] );
	            add_post_meta( $post_id, '_lng', $map["lng"] );
	        }
	        elseif ($lat && $lng) {
	        	if ($lat !== $map["lat"] || $lng !== $map["lng"]) {
		            update_post_meta( $post_id, '_lat', $map["lat"] );
		            update_post_meta( $post_id, '_lng', $map["lng"] );
	        	}
	        }
        }        
    }  
}