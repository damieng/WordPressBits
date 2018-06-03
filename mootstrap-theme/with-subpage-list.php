<?php if (!function_exists('get_header')) exit;
/*
Template Name: Page + auto sublist
*/
get_header();
if (have_posts()) {
	while (have_posts()) {
		the_post(); ?>
<div class="block" id="articles">
	<div class="article">
		<?php include (TEMPLATEPATH . '/item.php');
		$children = wp_list_pages('title_li=&child_of=' . $post->ID . '&echo=0');
		if ($children) { ?>
		<ul>
		<?php echo $children; ?>
		</ul>
		<?php } ?>
	</div>
	<?php comments_template(); ?>
</div>
<?php }
}
get_footer(); ?>