<?php if (!function_exists('get_header')) exit; ?>
<a title="<?php echo htmlspecialchars(get_comment_author($comment))?>" href="<?php comment_author_url()?>">
<?php if ($comment->comment_type == "trackback" || $comment->comment_type == "pingback") { ?>
	<img class="avatar" height="64" width="64" alt="<?php echo $comment->comment_type ?>" src="/images/trackback.png" />
<?php } else {	
	echo get_avatar($comment, 64);
} ?></a>
<?php if ($comment->comment_approved == '0') { ?>
<p>Your comment is awaiting moderation.</p>
<?php } ?>
<blockquote id="comment-<?php comment_ID();?>">
	<div class="comment-body">
	<?	comment_text(); ?>
	</div>
<cite><?php comment_author_link() ?> &ndash; <span class="muted"><?php comment_date('F jS, Y '); edit_comment_link('Edit','','');?></span></cite>
</blockquote>