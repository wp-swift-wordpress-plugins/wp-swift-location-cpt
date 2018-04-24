<?php
/*
 * Include the WordPress Admin API interface settings for this plugin.
 * This will declare all menu pages, tabs and inputs etc but it does not
 * handle any business logic related to form functionality.
 */
class WP_Swift_Locations_CPT_Admin_Interface_Settings {
	private $text_domain = 'wpswift-locations-cpt';
	private $text_domain_settings = 'wpswift-locations-cpt-settings';
    /*
     * Initializes the plugin.
     */
    public function __construct() {
        /*
         * Inputs
         */
        add_action( 'admin_menu', array($this, 'wp_swift_locations_cpt_add_admin_menu'), 20 );
        add_action( 'admin_init', array($this, 'wp_swift_locations_cpt_settings_init') );
        // add_action( 'init', array($this, 'validate_form'), 10, 3 );

                # Register ACF field groups that will appear on the options pages
        // add_action( 'init', array($this, 'acf_add_local_field_group') );
    }	

	/*
	 *
	 */
	public function wp_swift_locations_cpt_add_admin_menu(  ) { 

	    add_submenu_page( 'edit.php?post_type=location', 'Locations CPT Settings', 'Settings', 'manage_options', 'locations_cpt', array($this, 'wp_swift_locations_cpt_options_page')  );

	}

	/*
	 *
	 */
	// public function wp_swift_locations_cpt_settings_init_(  ) { 


	//     register_setting( $this->text_domain, 'wp_swift_locations_cpt_settings' );

	//     add_settings_section(
	//         'wp_swift_locations_cpt_plugin_page_section', 
	//         __( 'Set your preferences for the Locations CPT here', 'wp-swift-form-builder' ), 
	//         array($this, 'wp_swift_locations_cpt_settings_section_callback'), 
	//         $this->text_domain
	//     );

	// }


   /*
     * Register the settings that are used in the help tab
     *
     */
    public function wp_swift_locations_cpt_settings_init(  ) { 

        /******************************************************************************
         *
         * Register the settings for the 'Help Page' tab
         *
         ******************************************************************************/            
        // register_setting( $this->text_domain, 'wp_swift_locations_cpt_settings' );

        // add_settings_field( 
        //     'show_help_google_maps_page', 
        //     __( 'Google Maps', $this->text_domain ), 
        //     array($this, 'wp_swift_admin_menu_help_page_google_maps_render'), 
        //     'help-page', 
        //     'wp_swift_admin_menu_help_page_section'
        // );   

        /******************************************************************************
         *
         * Register the settings for the 'Google Maps' tab
         *
         ******************************************************************************/    

        register_setting(  $this->text_domain, 'wp_swift_google_map_settings' );

        add_settings_section(
            'wp_swift_admin_menu_google_map_page_section', 
            __( 'Google Map Settings', $this->text_domain ), 
            array($this, 'wp_swift_admin_menu_google_map_section_callback'), 
            $this->text_domain
        );

        add_settings_field( 
            'show_sidebar_options_google_map_api_key', 
            __( 'Google Map API key', $this->text_domain ), 
            array($this, 'show_sidebar_options_google_map_api_key_render'), 
            $this->text_domain, 
            'wp_swift_admin_menu_google_map_page_section',
            array( 'label_for' => 'google-map-api-key' )
        );

        add_settings_field( 
            'show_sidebar_options_google_map_style', 
            __( 'Google Map Style', $this->text_domain ), 
            array($this, 'show_sidebar_options_google_map_style_render'), 
            $this->text_domain, 
            'wp_swift_admin_menu_google_map_page_section',
            array( 'label_for' => 'google-map-style' ) 
        );

        add_settings_field( 
            'zoom_level_select', 
            __( 'Map Zoom Level', $this->text_domain ), 
            array($this, 'zoom_level_select_render'), 
            $this->text_domain, 
            'wp_swift_admin_menu_google_map_page_section' 
        );  

        // add_settings_field( 
        //     'show_sidebar_options_google_directions', 
        //     __( 'Include Directions', $this->text_domain ), 
        //     array($this, 'wp_swift_google_map_checkbox_directions_render'), 
        //     $this->text_domain, 
        //     'wp_swift_admin_menu_google_map_page_section',
        //     array( 'label_for' => 'google-directions' )
        // );

        // add_settings_field( 
        //     'wp_swift_google_map_checkbox_javascript', 
        //     __( 'Disable JavaScript', $this->text_domain ), 
        //     array($this, 'wp_swift_google_map_checkbox_javascript_render'),  
        //     $this->text_domain, 
        //     'wp_swift_admin_menu_google_map_page_section' 
        // );

        // add_settings_field( 
        //     'wp_swift_google_map_checkbox_css', 
        //     __( 'Disable CSS', $this->text_domain ), 
        //     array($this, 'wp_swift_google_map_checkbox_css_render'),  
        //     $this->text_domain, 
        //     'wp_swift_admin_menu_google_map_page_section' 
        // ); 

        /******************************************************************************
         *
         * Register the settings for the 'Google Maps' tab
         *
         ******************************************************************************/    

        // register_setting(  $this->text_domain_settings, 'wp_swift_location_cpt_settings' );

        // add_settings_section(
        //     'wp_swift_admin_menu_location_cpt_page_section', 
        //     __( 'Alt Settings', $this->text_domain_settings ), 
        //     array($this, 'wp_swift_admin_menu_location_cpt_section_callback'), 
        //     $this->text_domain_settings
        // );

        // add_settings_field( 
        //     'show_sidebar_options_location_cpt_api_key', 
        //     __( 'Google Map API key', $this->text_domain_settings ), 
        //     array($this, 'show_sidebar_options_location_cpt_api_key_render'), 
        //     $this->text_domain_settings, 
        //     'wp_swift_admin_menu_location_cpt_page_section',
        //     array( 'label_for' => 'google-map-api-key' )
        // );                    
    } 
	






/******************************************************************************
     *
     * Render the description and inputs that show on 
     * the 'Settings' page -> 'Google Map' tab
     *
     ******************************************************************************/

    /*
     * The description for the 'Google Map' tab
     */
    public function wp_swift_admin_menu_google_map_section_callback(  ) { 
        echo __( 'Use the fields below to set additional settings for Google Maps.', $this->text_domain );
    }

    /*
     * Render input field that allows the user to add a Google Maps API key
     */
    public function show_sidebar_options_google_map_api_key_render(  ) { 
        $options = get_option( 'wp_swift_google_map_settings' );
        $readonly = '';
        // if(!$this->show_sidebar_option('show_sidebar_options_google_map')) {
        //     $readonly = ' readonly';
        // }
        ?><input type="text" size="50" id="google-map-api-key" class="google-map-toggle-readonly" name="wp_swift_google_map_settings[show_sidebar_options_google_map_api_key]" value="<?php if (isset($options['show_sidebar_options_google_map_api_key'])) echo $options['show_sidebar_options_google_map_api_key']; ?>"<?php echo $readonly; ?>>
        <p><i><small>This is required and maps will not render without it.</small></i></p><?php
    }
    
    /*
     * Render textarea that allows the user to add a snazzy map json config
     */
    public function show_sidebar_options_google_map_style_render(  ) { 
        $options = get_option( 'wp_swift_google_map_settings' );
        $readonly = '';
        // if(!$this->show_sidebar_option('show_sidebar_options_google_map')) {
        //     $readonly = ' readonly';
        // }
        ?><textarea cols="49" rows="5" wrap="soft" id="google-map-style" class="google-map-toggle-readonly" name="wp_swift_google_map_settings[show_sidebar_options_google_map_style]"<?php echo $readonly; ?>><?php 
            if (isset($options['show_sidebar_options_google_map_style'])) {
                echo trim($options['show_sidebar_options_google_map_style']);
            } 
        ?></textarea>
        <?php if (!$readonly): ?>
            <p class="google-map-toggle-show"><small><i>Maps styles are available at </i><a href="https://snazzymaps.com/" target="_blank">Snazzy Maps</a>.</small></p>
        <?php endif;
    }

    /*
     * Render select box that determines map zoom level
     */
    public function zoom_level_select_render(  ) { 
        $options = get_option( 'wp_swift_google_map_settings' );
        ?><select id="zoom_level_select" name="wp_swift_google_map_settings[zoom_level_select]">
            <option value="0">Auto</option>
            <?php for ($i=5; $i < 21; $i++): ?>
                <option value="<?php echo $i ?>" <?php $this->is_select_set('zoom_level_select', $options, $i); ?>><?php echo $i ?></option>
            <?php endfor ?>
        </select><small> This determines the zoom level of the map.</small><?php

    }
    private function is_select_set($name, $options, $value) {
        if (isset($options[$name]) && $options[$name] == $value) {
            echo 'selected';
        }
    }

    /*
     *
     */
    public function wp_swift_google_map_checkbox_directions_render(  ) { 

        $options = get_option( 'wp_swift_google_map_settings' );
        ?>
        <input type="checkbox" name="wp_swift_google_map_settings[wp_swift_google_map_checkbox_directions]" value="1" <?php 
        if (isset($options['wp_swift_google_map_checkbox_directions'])) {
             checked( $options['wp_swift_google_map_checkbox_directions'], 1 );
         } 
        ?>>
        <small>Allow users add directions in a repeater field.</small>
        <?php

    }

    /*
     *
     */
    public function wp_swift_google_map_checkbox_javascript_render(  ) { 

        $options = get_option( 'wp_swift_google_map_settings' );
        ?>
        <input type="checkbox" name="wp_swift_google_map_settings[wp_swift_google_map_checkbox_javascript]" value="1" <?php 
        // checked( $options['wp_swift_google_map_checkbox_javascript'], 1 ); 
        if (isset($options['wp_swift_google_map_checkbox_javascript'])) {
             checked( $options['wp_swift_google_map_checkbox_javascript'], 1 );
         } 
        ?>>
        <small>You can disable JavaScript here if you wish to handle this yourself.</small>
        <?php

    }

    /*
     *
     */
    public function wp_swift_google_map_checkbox_css_render(  ) { 

        $options = get_option( 'wp_swift_google_map_settings' );
        ?>
        <input type="checkbox" name="wp_swift_google_map_settings[wp_swift_google_map_checkbox_css]" value="1" <?php //checked( $options['wp_swift_google_map_checkbox_css'], 1 );
            if (isset($options['wp_swift_google_map_checkbox_css'])) {
                checked( $options['wp_swift_google_map_checkbox_css'], 1 );
            }  ?>>
        <small>Same goes for CSS</small>
        <?php

    }









/******************************************************************************
     *
     * Render the description and inputs that show on 
     * the 'Settings' page -> 'Google Map' tab
     *
     ******************************************************************************/

    /*
     * The description for the 'Google Map' tab
     */
    public function wp_swift_admin_menu_location_cpt_section_callback(  ) { 
        echo __( 'Use the fields below to set additional settings for location_cpt.', $this->text_domain );
    }

    /*
     * Render input field that allows the user to add a Google Maps API key
     */
    public function show_sidebar_options_location_cpt_api_key_render(  ) { 
        $options = get_option( 'wp_swift_location_cpt_settings' );
        $readonly = '';
        // if(!$this->show_sidebar_option('show_sidebar_options_location_cpt')) {
        //     $readonly = ' readonly';
        // }
        ?><input type="text" size="50" id="google-map-api-key" class="google-map-toggle-readonly" name="wp_swift_location_cpt_settings[show_sidebar_options_location_cpt_api_key]" value="<?php if (isset($options['show_sidebar_options_location_cpt_api_key'])) echo $options['show_sidebar_options_location_cpt_api_key']; ?>"<?php echo $readonly; ?>>
        <p><i><small>This is required and maps will not render without it.</small></i></p><?php
    }



	/*
	 *
	 */
	// public function wp_swift_locations_cpt_settings_section_callback(  ) { 

	//     echo __( 'Locations CPT global settings', 'wp-swift-form-builder' );

	// }

	/*
	 *
	 */
	public function wp_swift_locations_cpt_options_page(  ) { 
	    ?>
	        <div id="form-builder-wrap" class="wrap">
		        <h2>WP Swift: Locations CPT</h2>

		        <form action='options.php' method='post'>
		            
		            <?php
		            settings_fields( $this->text_domain );
		            do_settings_sections( $this->text_domain );

		           	// settings_fields( $this->text_domain_settings );
		            // do_settings_sections( $this->text_domain_settings );
		            submit_button();
		            ?>

		        </form>
	        </div>
	    <?php 
	}
}
// Initialize the class
$locations_cpt_admin_interface_settings = new WP_Swift_Locations_CPT_Admin_Interface_Settings();