<?php
/**
 * Create the ajax nonce and url
 */
 function wp_swift_location_search_localize_script() {
    $location_search_ajax = array(
        // URL to wp-admin/admin-ajax.php to process the request
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        // generate a nonce with a unique ID so that you can check it later when an AJAX request is sent
        'security' => wp_create_nonce( 'location-search-nonce' ),
    );   
    if ( function_exists( 'foundationpress_scripts' ) ) {
        wp_localize_script( 'foundation', 'LocationSearchAjax', $location_search_ajax);
    }
}
add_action( 'wp_enqueue_scripts', 'wp_swift_location_search_localize_script', 20 );