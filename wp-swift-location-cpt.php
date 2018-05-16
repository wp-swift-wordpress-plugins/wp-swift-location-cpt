<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/wp-swift-wordpress-plugins
 * @since             1.0.0
 * @package           Wp_Swift_Location_Cpt
 *
 * @wordpress-plugin
 * Plugin Name:       WP Swift: Location CPT
 * Plugin URI:        https://github.com/GarySwift/wp-swift-location-cpt.git
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Gary Swift
 * Author URI:        https://github.com/wp-swift-wordpress-plugins
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-swift-location-cpt
 * Text Domain:       wp_swift_location_cpt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-swift-location-cpt-activator.php
 */
function activate_wp_swift_location_cpt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-swift-location-cpt-activator.php';
	Wp_Swift_Location_Cpt_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-swift-location-cpt-deactivator.php
 */
function deactivate_wp_swift_location_cpt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-swift-location-cpt-deactivator.php';
	Wp_Swift_Location_Cpt_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_swift_location_cpt' );
register_deactivation_hook( __FILE__, 'deactivate_wp_swift_location_cpt' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-swift-location-cpt.php';

/**
 * The classes that handles the admin interface
 */
require_once plugin_dir_path( __FILE__ ) . 'class-admin-interface-templates.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-interface-settings.php';

// echo "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi commodi accusamus odio quibusdam repellendus voluptatem deserunt exercitationem repellat asperiores maxime placeat officiis minus ducimus, quia magnam vitae ipsa pariatur. Pariatur! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio, ratione eius natus, incidunt dolorum itaque cum, rem consequatur officiis numquam, inventore iure. Voluptatum adipisci blanditiis, eum fugiat quo alias, at!";

/**
 * ******************** *
 * @author 	 Gary Swift *
 * ******************** *
 */

/**
 * The custom post type.
 *
 * @author 	 Gary Swift 
 * @since    1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'cpt/cpt.php';


require plugin_dir_path( __FILE__ ) . 'acf-field-groups/_location-details.php';

/*
*/
require plugin_dir_path( __FILE__ ) . '_shortcodes.php';

/**
 * The save post hook
 */
require plugin_dir_path( __FILE__ ) . 'includes/_save-post-location-hook.php';

/**
 * Create the ajax nonce and url
 */
require plugin_dir_path( __FILE__ ) . 'includes/_localize-script.php';

/**
 * The ajax call back
 */
require plugin_dir_path( __FILE__ ) . 'includes/_ajax.php';

/**
 * Process the ajax request
 */
require plugin_dir_path( __FILE__ ) . 'includes/_process-location-search-form.php';
require plugin_dir_path( __FILE__ ) . 'includes/_ajax-html-results.php';

/* Helpers */
require plugin_dir_path( __FILE__ ) . 'includes/_helper-functions.php';

# Register the Google API key to use with Advanced Custum Fields
add_action('acf/init', 'wp_swift_acf_init');
/**
 * It is necessary to register a Google API key in order to allow the Google API to load correctly. 
 *
 * Ref: https://www.advancedcustomfields.com/resources/google-map/
 *
 */
function wp_swift_acf_init() {
    $google_api_key = wp_swift_get_google_api_key();
    if( $google_api_key ) {
        acf_update_setting('google_api_key', $google_api_key);
    }       
}
/**
 * Retun the API key if it was set
 */
if (!function_exists("wp_swift_get_google_api_key")) {
    function wp_swift_get_google_api_key() {
        $options = get_option( 'wp_swift_google_map_settings' );
        if (isset($options['show_sidebar_options_google_map_api_key']) && $options['show_sidebar_options_google_map_api_key'] !== '') {
            return $options['show_sidebar_options_google_map_api_key'];
        }
        return false;       
    }
}





function add_async_forscript($url)
{
    if (strpos($url, '#asyncload')===false)
        return $url;
    else if (is_admin())
        return str_replace('#asyncload', '', $url);
    else
        return str_replace('#asyncload', '', $url)."' defer async='async"; 
}
add_filter('clean_url', 'add_async_forscript', 11, 1);




/**
 * Handle the ajax form submit
 */
// require_once plugin_dir_path( __FILE__ ) . '_ajax-form-callback.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_swift_location_cpt() {

	$plugin = new Wp_Swift_Location_Cpt();
	$plugin->run();

}
run_wp_swift_location_cpt();



/**
 * Move Media
 */
// function wp_swift_media_custom_menu_order() {
//     return array( 'index.php', 'upload.php' );
// }
// add_filter( 'custom_menu_order', '__return_true' );
// add_filter( 'menu_order', 'wp_swift_media_custom_menu_order' );

function get_contact_details($post_id = null) {
    $args = array(
        'posts_per_page'    => 1,
        'post_type'            => 'location',   
    );
    if ($post_id) {
        $args["post__in"] = array($post_id);
    }
    $posts = get_posts($args);
    $count = count($posts);

        // $name = '';
        // $email = '';
        // $tel = array();
        // $address = '';
        // $opening_hours = '';

         

    if ($count == 1 ) {
        // $post = $posts[0];
        // $post_id = $post->ID;

        $location = new WP_Swift_Location_Manager($posts[0]);
        // $location->set_email( $post->post_title );





        // if ( get_field('email', $post_id) ) {
        //     $email = get_field('email', $post_id);
        //     $location->set_email( get_field('email', $post_id) );
        // }
        // if ( have_rows('contact_numbers', $post_id) ) {
        //     while( have_rows('contact_numbers', $post_id) ) {
        //         the_row();             
        //         $label = get_sub_field('label'); 
        //         $number = get_sub_field('number');   
        //         $number = wp_swift_process_number_link($number);   
        //         $tel[] = array( 
        //             "label" => $label,
        //             "number" => $number,
        //         );  
        //     }
        //     $location->set_tel( $tel ); 
        // }  
    }
    else {
        $location = new WP_Swift_Location_Manager();
    }
    return $location;
}

require plugin_dir_path( __FILE__ ) . 'class-wp-swift-location-manager.php';

// [bartag foo="foo-value"]
function phone_func( $atts ) {
    $a = shortcode_atts( array(
        'label' => '',
        'tag' => '',
    ), $atts );
    $location = get_contact_details();
    ob_start();
    $location->get_tel($a['label'], $a['tag']);
    $html = ob_get_contents();
    ob_end_clean();
    
    return $html;
}
add_shortcode( 'phone', 'phone_func' );

function email_func( $atts ) {
    $a = shortcode_atts( array(
        'label' => '',
        'tag' => '',
    ), $atts );
    $location = get_contact_details();
    ob_start();
    $location->get_email($a['label'], $a['tag']);
    $html = ob_get_contents();
    ob_end_clean();
    
    return $html;
}
add_shortcode( 'email', 'email_func' );

function address_func( $atts ) {
    $location = get_contact_details();
    ob_start();
    $location->get_address();
    $html = ob_get_contents();
    ob_end_clean();
    
    return $html;
}
add_shortcode( 'address', 'address_func' );

function opening_hours_func( $atts ) {
    $location = get_contact_details();
    ob_start();
    $location->get_opening_hours();
    $html = ob_get_contents();
    ob_end_clean();
    
    return $html;
}
add_shortcode( 'opening_hours', 'opening_hours_func' );

function location_details_func( $atts ) {
    $location = get_contact_details();    
    return $location->get_details();
}
add_shortcode( 'location_details', 'location_details_func' );

// [bartag foo="foo-value"]
function bartag_func( $atts ) {


    return "foo = {$a['foo']}";
}
add_shortcode( 'bartag', 'bartag_func' );