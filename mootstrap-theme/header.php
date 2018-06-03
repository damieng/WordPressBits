<?php if (!function_exists('get_header')) exit;
global $start;
$start = microtime(true);
if (isset($_GET['s']) && $_GET['s'] == null) header("location: /"); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php include (TEMPLATEPATH.'/header-meta.php');
include (TEMPLATEPATH.'/page-title.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url');?>" />
<?php if (is_single() or is_page()) { ?>
    <link rel="pingback" href="<?php bloginfo('wpurl'); ?>/xmlrpc.php" />
<?php }
wp_head(); ?>
</head>
<body>
<?php flush();
include (TEMPLATEPATH.'/navbar.php'); ?>
<a href="#content" style="display:none">Skip to content</a>
<div class="container-fluid">
	<div class="row-fluid">
<?php include (TEMPLATEPATH.'/sidebar.php'); ?>
		<div class="span9" id="content">