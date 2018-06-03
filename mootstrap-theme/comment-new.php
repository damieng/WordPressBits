<?php if (!function_exists('get_header')) exit;
if (comments_open()) { ?>
<h3>Respond to this</h3>
<form action="/wp-comments-post.php" method="post" id="commentform" class="form-horizontal">
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
<?php if (false && $user_ID) { ?>
<p class="meta">Logged in as
	<a href="/wp-admin/profile.php"><?php echo htmlspecialchars($user_identity); ?></a>
	<a href="/wp-login.php?action=logout" title="Log out">(log out)</a>
</p>
<?php } else { ?>
<div id="commenttext">
<textarea name="comment" id="comment" placeholder="Continue the discussion."></textarea>
</div>
<div class="control-group">
<label>Name/alias <span>(Required, displayed)</span></label>
<input type="text" name="author" id="author" placeholder="Name" value="<?php echo esc_attr($comment_author); ?>" />
</div>
<div class="control-group">
<label>Email <span>(Required, not shown)</span></label>
<input type="text" name="email" id="email" placeholder="me@me.com" value="<?php echo esc_attr($comment_author_email); ?>" />
</div>
<div class="control-group">
<label>Website <span>(Optional, displayed)</span></label>
<input type="text" name="url" id="url" placeholder="http://somesite.com" value="<?php echo esc_attr($comment_author_url); ?>" />
<?php } ?>
</div>
<div class="control-group">
<label>&nbsp;</label>
<input name="submit" class="btn btn-primary" type="submit" id="submit" value="Leave response" />
</div>
<?php comment_id_fields();
do_action('comment_form', $post->ID); ?>
</form>
<?php } ?>