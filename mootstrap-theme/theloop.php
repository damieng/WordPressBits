<?php if (have_posts()) {
	while (have_posts()) {
		the_post(); ?>
<div class="post <?php $posttags=get_the_tags();
if ($posttags) {
  foreach($posttags as $tag)
    echo $tag->name . ' '; 
}?>">
<?php		include (TEMPLATEPATH . '/item.php'); ?>
</div>
<?php	}
}
include (TEMPLATEPATH . '/pagination.php');