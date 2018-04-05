<?php

/*
 * The ajax call back
 */
function wp_swift_submit_location_search_form_callback() {
    // echo "hello";
    // die();    
    // // write_log("wp_swift_submit_location_search_form_callback");
    check_ajax_referer( 'location-search-nonce', 'security' );


    if (isset($_POST)) {
        write_log($_POST);
    }

    $html = "Unable to find locations! [ wp_swift_submit_location_search_form_callback() _ajax.php ]";
    $markers = null;
    $header = null;
    $map = null;

    //     echo json_encode( $html );
    // die();

if (   (isset($_POST["lat"]) && $_POST["lat"] != '') 
    && (isset($_POST["lng"]) && $_POST["lng"] != '') 
    && isset($_POST["radius"])  ) {
    // && isset($_POST["cat"]) ) {
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];
    $radius = $_POST["radius"];
    $address = $_POST["address"];
    $cats = array();//$_POST["cat"];
    $locations = location_search_form_data($lat, $lng, $radius, $cats);   
    // write_log('[RESULTS]');
    // write_log($locations);

    $response = ajax_html_results($locations);
    write_log($locations_html);
    // $location_results = locations_html($locations, $address, $lat, $lng, $radius, $cats);
    // $html = $location_results["html"];
    // $markers = $location_results["markers"];
    // $header = $location_results["header"];
    // $map = $location_results["map"];   
    $html = "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus minima iste dolor sapiente suscipit explicabo odit, tempore doloremque nihil amet ab qui placeat similique. Quaerat eum maxime cupiditate laudantium animi.";
    $html = $locations_html;
}


    $_response = array(
        "html" => $html,
        "post" => $_POST,
        "lat" => $lat,
        "lng" => $lng,
        "radius" => $radius,
        "address" => $address,
        "cats" => $cats,
        // "markers" => $markers,
        // "header" => $header,
        // "map" => $map,
    );
    echo json_encode( $response );
    die();
}
add_action( 'wp_ajax_wp_swift_submit_location_search_form', 'wp_swift_submit_location_search_form_callback' );
add_action( 'wp_ajax_nopriv_wp_swift_submit_location_search_form', 'wp_swift_submit_location_search_form_callback' );


if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}

function locations_html($locations, $address, $lat, $lng, $radius, $cats) {
    ob_start();
    $html = '';
    $markerNodes = array();
    $count_locations =  count($locations);
    $locations_location = ($count_locations === 1 ? 'location':'locations');
    // $locations_location = 'locations';
    // if ($count_locations === 0) {
    //      $locations_location = 'location';
    // }
    write_log($count_locations);
    $header = 'Found '.$count_locations.' '.$locations_location.' within '.$radius.'KM of '.$address;
//     $header .= '<br> '.count($locations);

// $header .= '<br> '. "<pre>count_locations: "; var_dump($count_locations); echo "</pre>";
// echo "<pre>cats: "; var_dump($cats); echo "</pre>";
$address = urlencode($address);
$get_request = "?lat=$lat&lng=$lng&radius=$radius&address=$address";
$cats_str = '';


$query_args = array(
    "lat" => $lat,
    "lng" => $lng,
    "radius" => $radius,
    "address" => $address,
    "cat" => $cats,
);

foreach ($cats as $key => $cat) {
    $cats_str .= "&cat[]=$cat";
}
$cats_str = urlencode($cats_str);
$get_request .= $cats_str;

// $query_args = array(
//     "lat" => $lat,
//     "lng" => $lng,
//     "radius" => $radius,
// );

    $html = '<div id="results-header">'.$header.'</div>';
    // $header = '<div id="results-header">'.$header.'</div>';

    $map = '<div><select id="locationSelect" style="width: 20%; visibility: hidden"></select></div>
    <div id="map" style="width: 100%; height: 500px; position: relative;"></div>';
    $map = '';
    if ( $count_locations ): wp_reset_postdata(); ?>

        
        

                
                <?php foreach ($locations as $key => $post): 
                    // setup_postdata( $post );
                    $distance = number_format((float)$post->distance, 2, '.', '');
                    $recommended = '';
                    if ($key + 1 == 1) {
                        $recommended= ' recommended';
                    }

                    $permalink = add_query_arg( $query_args, get_the_permalink($post->ID) );
                    $permalink_dir = $permalink = add_query_arg( 'dir', 'true', $permalink );

                    $location = get_field('location', $post->ID);
                    if( !empty($location) ) {
                        $marker = array(
                            "id" => $post->ID,
                            "post_title" => $post->post_title,
                            "distance" => floatval($post->distance),
                            "location" => $location["address"], 
                            "lat" => floatval($location["lat"]), 
                            "lng" => floatval($location["lng"]),
                            
                        );
                        $markerNodes[] = $marker;
                    }
                    $html .= html_location($post, $permalink, $permalink_dir, $recommended, $key);
                endforeach;
                wp_reset_postdata();
            endif;//@end count($locations)

            
            // if ( function_exists( 'foundationpress_scripts' ) ) {
            //     wp_localize_script( 'foundation', 'markers', $markers);
            // }
            // wp_reset_query();
        
        // ob_end_clean();
        
        return array("html" => $html, "markers" => $markerNodes);    
}