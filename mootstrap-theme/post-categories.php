<?php if (!function_exists('get_header')) exit;
foreach (wp_get_post_categories($post->ID) as $categoryId) {
	$category_link = get_category_link($categoryId);
	$cat = get_category($categoryId);
	echo ' <a class="badge badge-info" title="'.htmlspecialchars($cat->name).' category" href="'.$category_link.'">'.$cat->name.'</a>';
} ?>