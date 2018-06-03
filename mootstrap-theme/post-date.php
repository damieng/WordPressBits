<?php if (!function_exists('get_header')) exit;
$published = get_the_time('F Y');
$updated = get_post_modified_time('F Y'); 
$publishedFull = get_the_time('j F Y');
$updatedFull = get_post_modified_time('j F Y'); ?>
<span title="First published
<?php echo $publishedFull;
if ($publishedFull != $updatedFull && is_page()) echo ' and last revised '.$updatedFull;?>">
<?php echo $published;
if ($published != $updated && is_page()) echo ' &ndash; '.$updated; ?>
</span>