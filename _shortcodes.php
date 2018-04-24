<?php
// [locations foo="foo-value"]
function locations_func( $atts ) {

	wp_swift_location_enqueue_script();
	/**
	 * Get the html
	 */
	$container_class = "grid-x grid-margin-x";
	$cell_class = "cell small-12 medium-6";//medium-auto	
	$use_cards = true;
	$show_map = true;
	$data_equalizer_watch = true;
	return wp_swift_location_cpt_html_locations($container_class, $cell_class, $show_map, $use_cards, $data_equalizer_watch);
}
add_shortcode( 'locations', 'locations_func' );


function wp_swift_location_cpt_html_locations($container_class, $cell_class, $show_map = true, $use_cards = false, $data_equalizer_watch = false) {
	ob_start();
	$posts = wp_swift_get_location_posts();

	$data_equalizer_watch_element = '';
	if ($data_equalizer_watch) {
		$data_equalizer_watch_element = " data-equalizer-watch";
		$data_equalizer_watch_container = ' data-equalizer data-equalize-on="medium"';
	}

	if ($container_class) {
		$container_class = ' class="'.$container_class.'"';
	}
	if ($cell_class) {
		$cell_class = ' class="'.$cell_class.'"';
	}	

	if ($posts && $show_map ) {
		$map = wp_swift_location_cpt_html_google_map($posts);
		echo $map;
	}
		
	if( $posts ):
		echo sprintf("<div%s%s>", $container_class, $data_equalizer_watch_container);
			foreach( $posts as $post ): 
				$location =  wp_swift_location_cpt_html_location($post, $data_equalizer_watch_element);

				if ($use_cards) {
					$location = wp_swift_location_cpt_html_card($location);
				}
				echo sprintf("<div%s>", $cell_class);
					echo $location;
				echo sprintf("</div>");

			endforeach;
		echo sprintf("</div>");
	endif;

	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
function wp_swift_location_cpt_html_card($post) {
	ob_start();
	?>

		<div class="card">
			<div class="card-section">
				<?php echo $post; ?>
			</div>	
		</div>				

	<?php
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}
function wp_swift_location_cpt_html_location($post, $data_equalizer_watch_element = '') {
	ob_start();
	?>

		<address class="location" style="font-style: normal">
			<header>
				<h5 class="location location-header"><?php echo $post->post_title; ?></h5>
			</header>
			
			<?php if ( get_field('address', $post->ID) ) : ?>
				<p class="location location-address"<?php echo $data_equalizer_watch_element ?>>
					<?php echo get_field('address', $post->ID); ?>
				</p>
			<?php endif; ?>

			<?php if ( get_field('email', $post->ID) ) : ?>
				<div>
					<a class="location location-link" href="mailto:<?php echo get_field('email', $post->ID); ?>"><?php echo get_field('email', $post->ID); ?></a>
				</div>
			<?php endif; ?>

			
			<?php if ( get_field('mobile', $post->ID) ) : ?>
				<div>
					<a class="location location-link" href="tel:<?php echo get_field('mobile', $post->ID); ?>"><?php echo get_field('mobile', $post->ID); ?></a>
				</div>
			<?php endif; ?>

			<?php if ( get_field('office_phone', $post->ID) ) : ?>
				<div>
					<a class="location location-link" href="tel:<?php echo get_field('office_phone', $post->ID); ?>"><?php echo get_field('office_phone', $post->ID); ?></a>
				</div>
			<?php endif; ?>
			
		</address>


		<?php 
		// $lat = get_post_meta($post->ID, '_lat', true );
  //       $lng = get_post_meta($post->ID, '_lng', true );
		edit_post_link( __( '(Edit)', 'foundationpress' ), '<div class="edit-link" style="float:right">', '</div>', $post->ID ); ?>

	<?php
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}


function wp_swift_location_cpt_html_google_map($posts) {
	ob_start();
	// $lat = '';
	// $lng = '';
	// $address = '';	
	$marker_nodes = array();

	if( $posts ):
		foreach( $posts as $post ): 
				
			// setup_postdata( $post );

			if( get_field( "map", $post->ID ) ) {
				$map = get_field( "map", $post->ID );
	            $marker = array(
	                "id" => $post->ID,
	                "post_title" => $post->post_title,
	                "distance" => null,
	                "location" => $map["address"], 
	                "lat" => floatval($map["lat"]), 
	                "lng" => floatval($map["lng"]),                
	            );
	        	$marker_nodes[] = $marker;				
			}
		endforeach;

		$count = count($posts );
		// wp_reset_postdata();

		

		if ( count($marker_nodes) ):
			
			// $lat = $marker_nodes[0]["lat"];
			// $lng = $marker_nodes[0]["lng"];
			// $marker_nodes = htmlspecialchars(json_encode($marker_nodes));
		endif; 
		$marker_nodes = htmlspecialchars(json_encode($marker_nodes));

		?>

		<div id="map-container" class="_hide" style="margin-bottom: 1rem">
			<h5>Dest</h5>
		    <input type="text" disabled id="search-destination-lat" name="lat" value="<?php //echo $location['lat']; ?>">
			<input type="text" disabled id="search-destination-lng" name="lng" value="<?php //echo $location['lng']; ?>">	
			<h5>Origin</h5>	
           	<input type="text" disabled id="search-origin-lat" name="lat" value="<?php //echo $lat ?>">
            <input type="text" disabled id="search-origin-lng" name="lng" value="<?php //echo $lng ?>">	

<?php 
/* 
			<div class="grid-x grid-margin-x">
				<div class="cell small-6">
					
				</div>
				<div class="cell small-6 ">
	                			
				</div>
			</div> 
*/ 
?>

			<?php if ($count === 1): ?>
				<div class="grid-x grid-margin-x">
					<div class="cell small-12">
						<select id="locationSelect" style="display: none"></select><?php //style="display: none" ?>
					</div>
					<div class="cell small-12">
		                <input type="text" name="address" id="addressInput" placeholder="Enter your location" onFocus="window.geolocate()" />				
					</div>
				</div>
			<?php else: ?>
				<div class="grid-x grid-margin-x">
					<div class="cell small-12 medium-4">
						<select id="locationSelect" style=""></select><!--  visibility: hidden -->
					</div>
					<div class="cell small-12 medium-8">
		                <input type="text" name="address" id="addressInput" placeholder="Enter your location" onFocus="window.geolocate()" />				
					</div>
				</div>				
			<?php endif ?>


			<div id="html-results"></div>

		    <div id="map" style="width: 100%; height: 400px; position: relative;" data-markers="<?php echo $marker_nodes; ?>"></div>
		</div>

	    <div class="grid-container" id="map-directions-container" style="margin: 1rem 0;border: 1px solid #DCDCDC; display: none;">
	        <div class="grid-x grid-margin-x">

	            <div class="cell">
	                <div id="map-directions"></div><?php // style="max-height: 400px; overflow: auto;" ?>
	                <div id="map-directions-link" style="padding: 1rem"></div>         
	            </div>
	        </div>
	    </div>
		<?php 
		

	endif;

	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

function wp_swift_map() {
	wp_swift_location_enqueue_script();
	echo  wp_swift_map_html( wp_swift_get_marker_nodes() ) ;
}

function wp_swift_map_html( $marker_nodes ) {
	ob_start();
	?>
		<div id="map-container">
			<div id="map" style="width: 100%; height: 400px; position: relative;" data-markers="<?php echo $marker_nodes; ?>"></div>
		</div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

function wp_swift_get_location_posts($posts_per_page = 50) {
	return get_posts(array(
		'posts_per_page'	=> $posts_per_page,
		'post_type'			=> 'location', 
	));	
}

function wp_swift_get_marker_nodes() {
	$posts = wp_swift_get_location_posts();
	if( $posts ):
		$marker_nodes = array();
		foreach( $posts as $post ): 

			if( get_field( "map", $post->ID ) ) {
				$map = get_field( "map", $post->ID );
	            $marker = array(
	                "id" => $post->ID,
	                "post_title" => $post->post_title,
	                "distance" => null,
	                "location" => $map["address"], 
	                "lat" => floatval($map["lat"]), 
	                "lng" => floatval($map["lng"]),                
	            );
	        	$marker_nodes[] = $marker;				
			}
		endforeach;

		$count = count($posts );

		
		if ( count($marker_nodes) ):
			// $lat = $marker_nodes[0]["lat"];
			// $lng = $marker_nodes[0]["lng"];
			// $marker_nodes = htmlspecialchars(json_encode($marker_nodes));
		endif; 
		return htmlspecialchars(json_encode($marker_nodes));
	endif;
	return '';
}

function wp_swift_location_enqueue_script() {
	/**
	 * Set up the Javascript
	 */
	$key = wp_swift_get_google_api_key();
	if ($key) {

		write_log(plugin_dir_path( __FILE__ ) . 'public/js/wp-swift-location-cpt-public.js');
		$js_version = filemtime( plugin_dir_path( __FILE__ ) . 'public/js/wp-swift-location-cpt-public.js' );
		wp_enqueue_script( 
			'wp-swift-location-cpt-js', 
			plugin_dir_url( __FILE__ ) . 'public/js/wp-swift-location-cpt-public.js', 
			array( 'jquery' ), 
			$js_version, 
			true 
		);
		wp_enqueue_script( 
			'googlemaps', 
			'https://maps.googleapis.com/maps/api/js?key='.$key.'&callback=window.initializeMapServices&libraries=places#asyncload',
			'',
			null, 
			true 
		);
	}
}