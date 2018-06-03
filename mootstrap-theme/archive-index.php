<?php if (!function_exists('get_header')) exit;
/*
Template Name: Archive index
*/
get_header(); ?>
<h1 class="page-header">Archive index</h1>

<div class="article" style="float: left; margin-right: 1em">
	<h2>By month</h2>
	<ul>
		<?php wp_get_archives('type=monthly'); ?>
	</ul>
</div>

<div class="article" style="float: left; margin-right: 1em">
	<h2>By tag</h2>
	<?php wp_tag_cloud('format=list'); ?>
</div>

<div class="article" style="float: left; margin-right: 1em">
	<h2>By category</h2>
	<ul>
		<?php wp_list_categories('title_li='); ?>
	</ul>
</div>
<?php get_footer(); ?>