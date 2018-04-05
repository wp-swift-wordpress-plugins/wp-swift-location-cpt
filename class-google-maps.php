<?php
/*
Plugin Name:        WP Swift: Google Map
Plugin URI:         https://github.com/GarySwift/wp-swift-admin-menu
Description:        Google Maps
Version:            1.0.0
Author:             Gary Swift
Author URI:         https://github.com/GarySwift
License:            GPL-2.0+
License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:        wp-swift-google-map
*/
/*
 * Declare a new class that will handle opening hors
 * 
 * @class       WP_Swift_Google_Maps
 *
 */
class WP_Swift_Google_Maps {
    private $text_domain = 'wp-swift-google-map';
    /*
     * Initializes the class.
     */
    public function __construct() { 

        # Register the Google API key to use with Advanced Custum Fields
        add_action('acf/init', array($this, 'wp_swift_acf_init'));

        # Register ACF field groups that will appear on the options pages
        add_action( 'init', array($this, 'acf_add_local_field_group') );

        # Create the submenu page that show in the side bar
        add_action( 'admin_menu', array($this, 'wp_swift_admin_menu_add_admin_menu') );



        # Load the JavaScript
        // add_action('admin_enqueue_scripts', array($this, 'google_maps_admin_enqueue_scripts'));

        # Shortcode for rendering the google-maps table
        // add_shortcode( 'google-map', array( $this, 'render_google_maps' ) );

        # Menu subpage and Help tab page
        add_action( 'admin_init', array($this, 'wp_swift_admin_menu_settings_init') );




        # Load the CSS
        // add_action( 'wp_enqueue_scripts', array($this, 'wp_swift_admin_menu_css_file') );

        # Load the admin CSS
        add_action( 'admin_enqueue_scripts', array($this, 'wp_swift_admin_menu_css_file_admin_style' ));

        # Load the JavaScript
        // add_action( 'wp_enqueue_scripts', array($this, 'enqueue_javascript') );

        # Load the admin JavaScript
        add_action('admin_footer', array($this, 'enqueue_admin_javascript'));

        # Create the JavaScript variables used in the Google Maps API directly in the footer
        add_action('wp_footer', array($this, 'set_map_js_vars_in_footer'));


        # Shortcodes for rendering the google maps.
        // add_shortcode( 'wp-swift-google-map', array( $this, 'render_google_map' ) );
        add_shortcode( 'google-map', array( $this, 'render_google_map' ) ); 

        # enqueue the google maps API in the footer
        add_action( 'init', array($this, 'enqueue_assets_googleapis_maps') );

    }




    /*
     * Retun the API key if it was set
     */
    public function get_api_key() {
        // if (get_field('google_api_key', 'option')) {
        //  return get_field('google_api_key', 'option');
        // } else {
        //  return false;
        // }
        $options = get_option( 'wp_swift_google_map_settings' );
        if (isset($options['show_sidebar_options_google_map_api_key']) && $options['show_sidebar_options_google_map_api_key'] !== '') {
            return $options['show_sidebar_options_google_map_api_key'];
        }
        return false;       
    }

    /**
     * Create the JavaScript variables used in the Google Maps API.
     * It will create a <script> block in the 'footer.php' that will be use in the 
     * API. 
     *
     * @setting map_zoom_level
     * @setting map_style
     * @setting contentString
     */
    public function set_map_js_vars_in_footer() {
        include "_set_map_js_vars_in_footer.php";
    }

    /*
     * It is necessary to register a Google API key in order to allow the Google API to load correctly. 
     *
     * Ref: https://www.advancedcustomfields.com/resources/google-map/
     *
     */
    public function wp_swift_acf_init() {
        $google_api_key = $this->get_api_key();
        if( $google_api_key ) {
            acf_update_setting('google_api_key', $google_api_key);
        }       
    }

    /*
     * Add the JavaScript file
     */
    public function enqueue_javascript () {
        // $options = get_option( 'wp_swift_admin_menu_settings' );
        
        // if (isset($options['wp_swift_admin_menu_checkbox_javascript'])==false) {
           wp_enqueue_script( $handle='wp-swift-google-maps', $src=plugins_url( '/assets/js/google-maps.js', __FILE__ ), $deps=null, $ver=null, $in_footer=true );
        // }
    }
    /*
     * Add the JavaScript file
     */
    public function enqueue_admin_javascript () {
        wp_enqueue_script( $handle='wp-swift-google-maps-admin', $src=plugins_url( '/assets/js/wp-swift-admin-menu-backend.js', __FILE__ ), $deps=null, $ver=null, $in_footer=true );
    }
    /*
     * Add the css file
     */
    public function wp_swift_admin_menu_css_file() {
        // $options = get_option( 'wp_swift_admin_menu_settings' );

        // if (isset($options['wp_swift_admin_menu_checkbox_css'])==false) {
            wp_enqueue_style('wp-swift-google-map-style', plugins_url( 'assets/css/wp-swift-admin-menu.css', __FILE__ ) );
        // }

    }

    public function wp_swift_admin_menu_css_file_admin_style() {
        wp_enqueue_style('wp-swift-google-map-admin-style', plugins_url( 'assets/css/wp-swift-admin-menu-css-file-admin-style.css', __FILE__ ) );
    }



    /**
     * A shortcode for rendering the google map.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_google_map( $attributes=null, $content = null ) {
        if ( function_exists('get_google_map') )  {
            return get_google_map( $attributes, $content );
        }
    }


    /*
     * Enqueue the google maps API in the footer
     */
    public function enqueue_assets_googleapis_maps() {
        include "_enqueue_assets_googleapis_maps.php";
    }   

    /**
     * A shortcode for rendering the google-map
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    // public function render_google_maps( $attributes = array(), $content = null ) {
    //     return get_google_map();
    // } 
        
    /*
     * The ACF field group for 'Google Maps'
     */ 
    public function acf_add_local_field_group() {
        require_once "acf-field-groups/_google-maps.php";
    }

    /*
     * 
     * Create the submenu page that show in the side bar
     *
     * The top level page is declared in 'wp-swift-admin-menu.php'
     * The submenu page uses Advanced Custom Fields API to register fields
     */
	public function wp_swift_admin_menu_add_admin_menu() {

		if(function_exists('acf_add_options_page')) { 
			/*
			 * Submenu page
			 */      
		    if($this->show_sidebar_option('show_sidebar_options_google_map')) {
	            acf_add_options_sub_page(array(
	                'title' => 'Google Map',
	                'slug' => 'google-map',
	                'parent' => $this->get_parent_slug(),
	            )); 
	        }

            // if($this->show_sidebar_option('show_sidebar_options_google_map')) {
            //     acf_add_options_sub_page(array(
            //         'title' => 'Google Map',
            //         'slug' => 'google-map',
            //         'parent' => $this->menu_slug,
            //     )); 
            // }  
	    }
	}  

    /*
     * This determines the location the menu links
     * They are listed under Settings unless the other plugin 'wp_swift_admin_menu' is activated
     */
    private function get_parent_slug() {
        if ( get_option( 'wp_swift_admin_menu' ) ) {
            return get_option( 'wp_swift_admin_menu' );
        }
        else {
            return 'options-general.php';
        }
    }	

    /**
     * A helper fuction that tests if an option is set
     *
     * @param  string  $option     	The name of the WordPress option field
     *
     * @return boolean				If the option is set   
     */
	private function show_sidebar_option($option) {
		$options = get_option( 'wp_swift_admin_menu_settings' );
		if (isset($options[$option]) && $options[$option]) {
			return true;
		}
		return false;
	}   

    /*
     * Register the settings that are used in the help tab
     *
     */
    public function wp_swift_admin_menu_settings_init(  ) { 

        /******************************************************************************
         *
         * Register the settings for the 'Help Page' tab
         *
         ******************************************************************************/            

        add_settings_field( 
            'show_help_google_maps_page', 
            __( 'Google Maps', $this->text_domain ), 
            array($this, 'wp_swift_admin_menu_help_page_google_maps_render'), 
            'help-page', 
            'wp_swift_admin_menu_help_page_section'
        );   

        /******************************************************************************
         *
         * Register the settings for the 'Google Maps' tab
         *
         ******************************************************************************/    

        register_setting( 'google-map', 'wp_swift_google_map_settings' );

        add_settings_section(
            'wp_swift_admin_menu_google_map_page_section', 
            __( 'Google Map Settings', $this->text_domain ), 
            array($this, 'wp_swift_admin_menu_google_map_section_callback'), 
            'google-map'
        );

        add_settings_field( 
            'show_sidebar_options_google_map_api_key', 
            __( 'Google Map API key', $this->text_domain ), 
            array($this, 'show_sidebar_options_google_map_api_key_render'), 
            'google-map', 
            'wp_swift_admin_menu_google_map_page_section',
            array( 'label_for' => 'google-map-api-key' )
        );

        add_settings_field( 
            'show_sidebar_options_google_map_style', 
            __( 'Google Map Style', $this->text_domain ), 
            array($this, 'show_sidebar_options_google_map_style_render'), 
            'google-map', 
            'wp_swift_admin_menu_google_map_page_section',
            array( 'label_for' => 'google-map-style' ) 
        );

        add_settings_field( 
            'zoom_level_select', 
            __( 'Map Zoom Level', $this->text_domain ), 
            array($this, 'zoom_level_select_render'), 
            'google-map', 
            'wp_swift_admin_menu_google_map_page_section' 
        );  

        add_settings_field( 
            'show_sidebar_options_google_directions', 
            __( 'Include Directions', $this->text_domain ), 
            array($this, 'wp_swift_google_map_checkbox_directions_render'), 
            'google-map', 
            'wp_swift_admin_menu_google_map_page_section',
            array( 'label_for' => 'google-directions' )
        );

        add_settings_field( 
            'wp_swift_google_map_checkbox_javascript', 
            __( 'Disable JavaScript', $this->text_domain ), 
            array($this, 'wp_swift_google_map_checkbox_javascript_render'),  
            'google-map', 
            'wp_swift_admin_menu_google_map_page_section' 
        );

        add_settings_field( 
            'wp_swift_google_map_checkbox_css', 
            __( 'Disable CSS', $this->text_domain ), 
            array($this, 'wp_swift_google_map_checkbox_css_render'),  
            'google-map', 
            'wp_swift_admin_menu_google_map_page_section' 
        );             
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
        if(!$this->show_sidebar_option('show_sidebar_options_google_map')) {
            $readonly = ' readonly';
        }
        ?><input type="text" size="50" id="google-map-api-key" class="google-map-toggle-readonly" name="wp_swift_google_map_settings[show_sidebar_options_google_map_api_key]" value="<?php if (isset($options['show_sidebar_options_google_map_api_key'])) echo $options['show_sidebar_options_google_map_api_key']; ?>"<?php echo $readonly; ?>>
        <p><i><small>This is required and maps will not render without it.</small></i></p><?php
    }
    
    /*
     * Render textarea that allows the user to add a snazzy map json config
     */
    public function show_sidebar_options_google_map_style_render(  ) { 
        $options = get_option( 'wp_swift_google_map_settings' );
        $readonly = '';
        if(!$this->show_sidebar_option('show_sidebar_options_google_map')) {
            $readonly = ' readonly';
        }
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
    # @end Render 'Settings' page -> 'Google Map' tab
    /*
     * This will output formatted html
     */
    public function wp_swift_admin_menu_help_page_google_maps_render() {
?><p>To render the <b>Google Map</b> onto a webpage ther are two options: PHP code and WordPress <a href="https://codex.wordpress.org/Shortcode" target="_blank">Shortcodes</a>.</p>

<h4>PHP Code</h4>
<pre class="prettyprint lang-php custom">
// Render the Google Map

if (function_exists('get_google_map')) {
  echo get_google_map();
}

// Or you can optionally use parameters

if (function_exists('get_google_map')) {
  echo get_google_map( array('address' => true) );
}
</pre>

<h4>WordPress Shortcodes</h4>
Using shortcodes, you have the following options:
<pre class="prettyprint custom">
// WordPress shortcode

[google-map]

// This also accepts parameters

[google-map address="true"]
</pre>
<br>
<p>In both examples, code and shortcode, the parameters are used to display additonal content.</p><?php 
    }//@end function wp_swift_admin_menu_help_page_google_maps_render       
}//@end class WP_Swift_Google_Maps


// Initialize the class
$wp_swift_google_maps = new WP_Swift_Google_Maps();

/**
 * A function for getting the html for rendering the google map.
 *
 * @param  array   $attributes  Shortcode attributes.
 * @param  string  $content     The text content for shortcode. Not used.
 *
 * @return string  The shortcode output
 */
if ( !function_exists('get_google_map') )  {
    function get_google_map( $attributes=null, $content = null ) {
        $class = 'acf-map';
        if (isset($attributes['class'])) {
            $class = $attributes['class'];
        }        
        $options = get_option( 'wp_swift_google_map_settings' );
        ob_start();

        if (isset($options['show_sidebar_options_google_map_api_key']) && $options['show_sidebar_options_google_map_api_key'] != ''):
            
            $location = get_field('map', 'option');
            if( !empty($location) ):
            ?>
                <?php if (isset($attributes['address'])): ?>
                    <p><?php echo $location['address']; ?></p>
                <?php endif ?>
                
                <div class="<?php echo $class; ?>">
                    <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="callout">
                <h5>Sorry, there was a problem with the Google API key.</h5>
            </div>
            <?php
        endif;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }   
}