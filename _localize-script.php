<?php
/**
 * Create the ajax nonce and url
 */
 function wp_swift_form_builder_localize_script() {
    $js_version = filemtime( get_stylesheet_directory().'/dist/assets/js/app.js' );
	$form_builder_ajax = array(
        // URL to wp-admin/admin-ajax.php to process the request
        // 'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'ajaxurl' => plugin_dir_path( __FILE__ ) . 'ajax.php',
        // generate a nonce with a unique ID so that you can check it later when an AJAX request is sent
        'security' => wp_create_nonce( 'form-builder-nonce' ),
        // debugging info
        'updated' => date ("H:i:s - F d Y", $js_version),
    ); 
    echo "<pre>"; var_dump($form_builder_ajax); echo "</pre>";  
    $form_builder_date_picker = array( 'format' => FORM_BUILDER_DATE_FORMAT);
    if ( function_exists( 'foundationpress_scripts' ) ) {
    	wp_localize_script( 'foundation', 'FormBuilderAjax', $form_builder_ajax);
        // wp_localize_script( 'foundation', 'FormBuilderDatePicker', $form_builder_date_picker);
    }
    else {
		$js_file = 'public/js/ajax.js';
		$js_file_path = plugin_dir_path( __FILE__ ).$js_file;
		$js_version = filemtime( $js_file_path ); 
    	wp_enqueue_script( 'form-builder-ajax', plugin_dir_url( __FILE__ ) . $js_file, array(), $js_version, true );
    	wp_localize_script( 'form-builder-ajax', 'FormBuilderAjax', $form_builder_ajax);
        wp_localize_script( 'form-builder-ajax', 'FormBuilderDatePicker', $form_builder_date_picker);
    } 
}
add_action( 'wp_enqueue_scripts', 'wp_swift_form_builder_localize_script', 20 );