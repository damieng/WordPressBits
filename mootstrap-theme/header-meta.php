	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="referrer" content="unsafe-url">
<?php if (is_single() || is_page()) { ?>
	<meta name="description" content="<?php while(have_posts()) { the_post(); the_excerpt_rss(); } ?>" />
	<meta name="twitter:title" content="<?php the_title();?>" />
	<meta name="twitter:url" content="<?php the_permalink();?>" />
	<meta name="og:title" content="<?php the_title();?>" />
	<meta name="og:url" content="<?php the_permalink();?>" />
	<meta name="og:description" content="<?php the_excerpt();?>" />
<?php } else { ?>
	<meta name="description" content="<?php bloginfo('description'); ?>" />
<?php }
if (is_single() || is_page() || is_home()) { ?>
	<meta name="googlebot" content="index,noarchive,follow,noodp" />
<?php } else { ?>
	<meta name="googlebot" content="noindex,noarchive,follow,noodp" />
<?php } $robots = get_robots(); ?>
	<meta name="robots" content="<?php echo $robots ?>" />