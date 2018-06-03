<h2>
<?php $title = get_the_title();
$doLink = !is_single() && !is_page();
if ($doLink) { ?>
	<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent link to <?php echo $title?>"><?php echo $title ?></a>
<?php } else { echo $title; } ?>
	&nbsp;<small><?php edit_post_link('Edit', '', ''); ?></small>
</h2>
<p class="post-meta">
	<?php include (TEMPLATEPATH . '/post-date.php');
	if (!is_page()) {
			include (TEMPLATEPATH . '/post-categories.php');
			include (TEMPLATEPATH . '/post-tags.php');
	} ?>
	<span class="pull-right">
	<?php if(function_exists('the_views')) { the_views(); }
	if ($post->comment_status == 'open' && wp_count_comments($post->ID)->approved > 0) { ?>
		&nbsp;
		<?php comments_popup_link('', 'one response', '% responses', 'responseCount', '');	
	} ?>
	</span>
</p>
<?php the_content('Read the rest of this article'); ?>