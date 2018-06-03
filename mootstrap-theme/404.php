<?php require (dirname(__FILE__) . '/../../../wp-blog-header.php');
get_header();

function get_search_phrase($referrer_url, $d) {
	$query_array = array();
	$query_terms = null;
	
	// Get raw query
	$query = explode($d . '=', $referrer_url);
	$query = explode('&', $query[1]);
	$query = urldecode($query[0]);
	
	// Remove quotes, split into words, and format for HTML display
	$query = str_replace("'", '', $query);
	$query = str_replace('"', '', $query);
	$query_array = preg_split('/[\s,\+\.]+/', $query);
	$query_terms = implode(' ', $query_array);
	return htmlspecialchars(urldecode(trim($query_terms)));
} 

$query = '';
if ($_SERVER['HTTP_REFERER']) {
	$referrer_url = attribute_escape($_SERVER['HTTP_REFERER']);
	$url = parse_url($referrer_url);

	if (strstr($url['host'], 'google'))
		$query = get_search_phrase($referrer_url, 'q');
	else
	if (strstr($url['host'], 'yahoo'))
		$query = get_search_phrase($referrer_url, 'p');
	else
	if (strstr($url['host'], 'live'))
		$query = get_search_phrase($referrer_url, 'q');

	$query = trim($query);
}

$slug_terms = urldecode($_SERVER['REQUEST_URI']);
$slug_terms = substr($slug_terms, 1, strlen($slug_terms) - 1);
$search = array('@[\/]+@', '@(\..*)@', '@[\-]+@', '@[\_]+@', '@[\s]+@', '@archives@','@(\?.*)@', '/\d/');
$replace = array(' ', '', ' ', ' ', ' ', '', '','');
//$slug_terms = trim(preg_replace($search, $replace, $slug_terms));?>
<div class="content">	
	<h1>Not found (404)</h1>
	<div class="alert">Sorry we can't find that page.</div>
	<div />
	<?php if (!empty($query) || !empty($slug_terms)) : ?>
 	<div class="information">Why not try searching:
		<ul>
		<?php if (!empty($query)) : ?>
			<li>
				<a href="<?php echo get_bloginfo('home'); echo '?s='; echo urlencode($query); ?>">locally using the same terms</a>
					(<b><?php echo htmlspecialchars($query); ?></b>).
			</li>
			<?php endif;
			if (!empty($slug_terms)) : ?>
			<li>
				<a href="<?php echo get_bloginfo('home'); echo '?s='; echo urlencode($slug_terms); ?>">using url slug terms</a>
					(<b><?php echo htmlspecialchars($slug_terms); ?></b>).
			</li>
		<?php endif; ?>
		</ul>
	</div>
	<?php endif; ?>
</div>
<?php get_footer(); ?>