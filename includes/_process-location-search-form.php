<?php
function location_search_form_data($center_lat, $center_lng, $radius, $location_cat) {
	global $wpdb;
	$haversine = array("km" => 6371, "miles" => 3959);
	$limit = 1;

    $term_ids = '';
    // foreach ($location_cat as $key => $term_id) {
    // 	$term_ids .= $term_id . ', ';
    // }
    // $term_ids = rtrim($term_ids, ', ');

	$querystr = sprintf("
	SELECT DISTINCT
		{$wpdb->prefix}posts.*,
		lat.meta_value AS lat, 
		lng.meta_value AS lng,
		location.meta_value AS location,
		( %s * acos( cos( radians('%s') ) * cos( radians( lat.meta_value ) ) * cos( radians( lng.meta_value ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat.meta_value ) ) ) ) AS distance
	FROM {$wpdb->prefix}posts
	LEFT JOIN {$wpdb->prefix}postmeta as lng
	    ON {$wpdb->prefix}posts.ID = lng.post_id
	LEFT JOIN {$wpdb->prefix}postmeta as lat
	    ON {$wpdb->prefix}posts.ID = lat.post_id
	LEFT JOIN {$wpdb->prefix}postmeta as location
	    ON {$wpdb->prefix}posts.ID = location.post_id 
	-- LEFT JOIN {$wpdb->prefix}term_relationships
	-- 	ON({$wpdb->prefix}posts.ID = {$wpdb->prefix}term_relationships.object_id)
	-- LEFT JOIN {$wpdb->prefix}term_taxonomy
	-- 	ON({$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id)
	WHERE 1
	AND lat.meta_key = '_lat'
	AND lng.meta_key = '_lng'
	AND location.meta_key = 'map'
	-- AND {$wpdb->prefix}term_taxonomy.term_id IN (%s)
	-- AND {$wpdb->prefix}term_taxonomy.taxonomy = 'location_cat'
	AND {$wpdb->prefix}posts.post_status = 'publish'
	AND {$wpdb->prefix}posts.post_type = 'location'
	HAVING distance < '%s'
	ORDER BY distance 
	LIMIT 0 , %s",
	$haversine["km"], $center_lat, $center_lng, $center_lat, $term_ids, $radius, $limit);

	return $wpdb->get_results($querystr, OBJECT);
}