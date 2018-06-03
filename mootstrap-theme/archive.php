<?php if (!function_exists('get_header')) exit;
get_header();?>
<h1><?php
if (is_category()) { echo htmlspecialchars(ucfirst(single_cat_title('',false))) ?><?php }
elseif (is_tag()) { echo htmlspecialchars(ucfirst(single_tag_title('',false))) ?><?php }
elseif (is_day()) { the_time('F jS, Y'); }
elseif (is_month()) { the_time('F, Y'); }
elseif (is_year()) { the_time('Y'); }?> articles</h1>
<?php include (TEMPLATEPATH . '/theloop.php');
get_footer(); ?>