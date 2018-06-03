<title><?
	if (is_archive()) { echo 'Articles '; }
	if (is_search()) { echo 'Search results for &ldquo;' . get_search_query() . '&rdquo;'; }
	else {
		if (is_tag() || is_category()) { echo ' about '; }
		if (is_month()) the_time('F Y'); else wp_title('');
		if (is_home()) { echo bloginfo('description'); }
	}
	if (is_paged()) { echo ' - page ' . $paged; }
	?> &raquo; <?php bloginfo('name')?></title>