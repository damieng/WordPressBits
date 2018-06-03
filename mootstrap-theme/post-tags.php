<?php if (!function_exists('get_header')) exit;
foreach (wp_get_post_tags($post->ID) as $tag) {
	$tag_link = get_tag_link($tag->term_id);
	echo ' <a class="badge badge-tag" title="'.htmlspecialchars($tag->name).' tag" href="'.$tag_link.'">'.$tag->name.'</a>';
} ?>