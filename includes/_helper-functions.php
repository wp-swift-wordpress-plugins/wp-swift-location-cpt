<?php
if (!function_exists('wp_swift_format_number_link')) {
	function wp_swift_format_number_link($number_array) {
	    $number = $number_array["country_code"] . ' ' . $number_array["area_code"] . ' ' . $number_array["number"]; 
	    // Remove non-numeric chars (except +)
	    $number_format = preg_replace("/[^0-9,+]/", "", $number );
	    return '<a href="tel:'.$number_format.'" class="contact-number">'.$number.'</a>';
	}
}