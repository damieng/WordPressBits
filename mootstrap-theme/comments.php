<?php if (!function_exists('get_header')) exit;
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (post_password_required()) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php return;
	}
	
	if (have_comments()) { ?>
<div id="comments">
	<h3><?php comments_number('', 'One response', '% responses' );?> <?php post_comments_feed_link('&nbsp;'); ?></h3>
	
	<ol>
	<?php foreach ($comments as $comment) { ?>
		<li<?php if ($comment->user_id == $post->post_author) echo ' class="byauthor"'; ?>>
			<?php include (TEMPLATEPATH . '/comment.php'); ?>
		</li>
	<?php } ?>
	</ol>	
</div>
<?php }
include (TEMPLATEPATH . '/comment-new.php');  ?>
