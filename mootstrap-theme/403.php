<?php require (dirname(__FILE__) . '/../../../wp-blog-header.php');
get_header(); 
$server = $_SERVER['SERVER_NAME'];
$uri = $_SERVER['REQUEST_URI'];
$bad_link = $server . $uri;
$referer = $_SERVER['HTTP_REFERER']; ?>
<div class="content">
	<h1>Forbidden (403)</h1>
	<p class="alert">Direct downloads not permitted.</p>
	<p>
		Seems those naughty folks over at
		<?php echo htmlspecialchars($referer); ?>
		want to use my bandwidth to serve up
		<?php echo htmlspecialchars($uri); ?>
	</p>
</div>
<?php get_footer(); ?>