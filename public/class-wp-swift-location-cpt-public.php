<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/wp-swift-wordpress-plugins
 * @since      1.0.0
 *
 * @package    Wp_Swift_Location_Cpt
 * @subpackage Wp_Swift_Location_Cpt/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Swift_Location_Cpt
 * @subpackage Wp_Swift_Location_Cpt/public
 * @author     Gary Swift <garyswiftmail@gmail.com>
 */
class Wp_Swift_Location_Cpt_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-swift-location-cpt-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// $key = wp_swift_get_google_api_key();
		// if ($key) {

		// 	$js_version = filemtime( plugin_dir_path( __FILE__ ) . 'js/wp-swift-location-cpt-public.js' );
		// 	wp_enqueue_script( 
		// 		$this->plugin_name, 
		// 		plugin_dir_url( __FILE__ ) . 'js/wp-swift-location-cpt-public.js', 
		// 		array( 'jquery' ), 
		// 		$js_version, 
		// 		true 
		// 	);
		// 	wp_enqueue_script( 
		// 		'googlemaps', 
		// 		'https://maps.googleapis.com/maps/api/js?key='.$key.'&callback=window.initializeMapServices&libraries=places#asyncload',
		// 		'',
		// 		null, 
		// 		true 
		// 	);
		// }
	}
}