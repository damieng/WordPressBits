<?php if (!function_exists('get_header')) exit;
get_header(); ?>
<div class="post <?php $posttags=get_the_tags();
if ($posttags) {
  foreach($posttags as $tag)
    echo $tag->name . ' '; 
}?>">
<?		include (TEMPLATEPATH . '/item.php');
		comments_template(); ?>
</div>
<?php get_footer(); ?>