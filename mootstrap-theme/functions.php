<?
require_once('wp-list-pages-bootstrap.php');

if (function_exists('register_sidebar')) {
   register_sidebar(array(
       'before_widget' => '<div id="%1$s">',
       'after_widget' => '</div>',
       'before_title' => '<div class="nav-header">',
       'after_title' => '</div>',
   ));
}

// Remove WordPress spammy crap
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script' );
remove_action('admin_print_styles', 'print_emoji_styles' );
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

function trim_excerpt($text) {
  return rtrim($text,'[...]');
}
add_filter('get_the_excerpt', 'trim_excerpt');

function get_robots() {
	return (is_single() || is_page() || is_home())
		? 'all,index,follow' : 'noindex,follow';
}

function kill_scripts() {
  if (!is_admin()) {
    wp_deregister_script('l10n');
    wp_deregister_script('jquery');
  }
}

function my_deregister_scripts(){
	wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'my_deregister_scripts');

add_action('wp_print_scripts', 'kill_scripts');

function explain_less_login_issues() { return 'Error!';}
add_filter('login_errors', 'explain_less_login_issues');

?>
