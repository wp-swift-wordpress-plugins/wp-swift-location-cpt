<?php
function ajax_html_results($posts) {
	if ( count($posts) === 1 ) {
		$results = array();
		$post = $posts[0];
		$results["destination"] = array( "lat" => floatval($post->lat), "lng" => floatval($post->lng) );
		//$results["html"] = sprintf("<p>The closest location to you is <strong>%s</strong> which is %dKM away from you.</p>", $post->post_title, round($post->distance) );
		$results["html"] = sprintf("<p>The closest location to you is <strong>%s</strong>.</p>", $post->post_title );
		write_log($results);
		return $results;
	}
	return null;
}
function _ajax_html_results($posts) {
	ob_start();
	?><!-- html content here -->

	<?php if ($posts): ?>
		
		<table>
			<thead>
				<tr>
					<th>Office</th>
					<th>Location</th>
					<th>Distance</th>
					<th>Latitude</th>
					<th>Longitude</th>
				</tr>
			</thead>
			<tbody>

				<?php foreach ($posts as $key => $post): ?>
					<?php 
						$location = unserialize($post->location);
						$distance = round($post->distance) . " KM";
					?>

					<tr>
						<td><a href="#" class="js-show-route" data-lat="<?php echo $post->lat; ?>" data-lng="<?php echo $post->lng; ?>" onclick="return showRouteLinkClicked(this);"><?php echo $post->post_title; ?></a></td>
						<td><?php echo $location["address"]; ?></td>
						<td><?php echo $distance; ?></td>
						<td><?php echo $post->lat; ?></td>
						<td><?php echo $post->lng; ?></td>
					</tr>

				<?php endforeach ?>

			</tbody>
		</table>
		
	<?php endif ?>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}