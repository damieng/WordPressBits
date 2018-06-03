<?php if (!function_exists('get_header')) exit;
get_header(); ?>
<h1>Search results for &ldquo;<?php the_search_query(); ?>&rdquo;</h1>
<?php if ($wp_query->found_posts == 0) { ?>
<p>I searched the libraries through pages and posts.<br /><br />
Across archives and comments, through endless byte-streams of data.<br /><br />
All whispered to me... <b>no match found</b>.</p>
<?php } else {
	$posts=query_posts($query_string . '&posts_per_page=10');
	include (TEMPLATEPATH . '/theloop.php');
}
get_footer(); ?>