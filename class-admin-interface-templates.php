<?php
/*
 * Include the WordPress Admin API interface settings for this plugin.
 * This will declare all menu pages, tabs and inputs etc but it does not
 * handle any business logic related to form functionality.
 */
class WP_Swift_Locations_CPT_Admin_Interface {

    /*
     * Initializes the plugin.
     */
    public function __construct() {
        /*
         * Inputs
         */
        add_action( 'admin_menu', array($this, 'wp_swift_form_builder_add_admin_menu'), 20 );
        // add_action( 'admin_init', array($this, 'wp_swift_form_builder_settings_init') );
    }	

	/*
	 *
	 */
	public function wp_swift_form_builder_add_admin_menu(  ) { 

	    add_submenu_page( 'edit.php?post_type=location', 'Form Builder Templates', 'Templates', 'manage_options', 'form_builder_templates', array($this, 'wp_swift_form_builder_templates')  );
	}

	// public function wp_swift_form_builder_templates(  ) { 

	// }



	/*
	 *
	 */
	public function wp_swift_form_builder_settings_init(  ) { 

	    register_setting( 'form-builder-templates', 'wp_swift_form_builder_settings' );
	    add_settings_section(
	        'wp_swift_form_builder_plugin_page_section', 
	        __( 'Starter Template Forms', 'wp-swift-form-builder' ), 
	        array($this, 'wp_swift_form_builder_settings_section_callback'), 
	        'form-builder-templates'
	    );
	}

	/*
	 *
	 */
	public function wp_swift_form_builder_settings_section_callback(  ) { 
	    ?>

	    <p><?php echo __( 'Preset Form Builder templates are shown below. Click each button to create a form with the inputs shown.', 'wp-swift-form-builder' ); ?></p>
	    <p><?php echo __( 'Use these as starter points to see how forms are created. You can easily add/remove inputs to suit your needs.', 'wp-swift-form-builder' ); ?></p>
	    <?php
	}

		/*
	 *
	 */
	public function wp_swift_form_builder_checkbox_debug_mode_render(  ) { 

	    $options = get_option( 'wp_swift_form_builder_settings' );
	    ?><input type="checkbox" name="wp_swift_form_builder_settings[wp_swift_form_builder_checkbox_debug_mode]" value="1" <?php 
	    	if (isset($options['wp_swift_form_builder_checkbox_debug_mode'])) {
	         	checked( $options['wp_swift_form_builder_checkbox_debug_mode'], 1 );
	     	} 
	    ?>>
	    <small><b>Do not use on live sites!</b></small><br>
	    <small>You can set this to debug mode if you are a developer. This will skip default behaviour such as sending emails.</small><?php

	}
	/*
	 *
	 */
	public function wp_swift_form_builder_templates(  ) { 

	}
}
// Initialize the class
$locations_cpt_admin_interface = new WP_Swift_Locations_CPT_Admin_Interface();