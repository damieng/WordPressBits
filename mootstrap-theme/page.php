<?php if (!function_exists('get_header')) exit;
get_header();
if (have_posts()) {
	while (have_posts()) {
		the_post();
		include (TEMPLATEPATH . '/item.php');
		comments_template();
	}
}
get_footer(); ?>