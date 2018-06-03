<?php class TerWalkerPage extends Walker_Page {
	function start_lvl(&$output,$depth){
		$indent = str_repeat("\t",$depth);
		if($depth < 1) $dropdown_menu = ' dropdown-menu';
		$output .= "\n$indent<ul class=\"sub-menu$dropdown_menu\">\n";
	}
	function start_el(&$output,$page,$depth,$args,$current_page){
		if($depth) $indent = str_repeat("\t", $depth);
		else $indent = '';
		extract($args, EXTR_SKIP);
		$css_class = array('page_item', 'page-item-'.$page->ID);
		if(!empty($current_page)){
			$_current_page = get_page($current_page);
			_get_post_ancestors($_current_page);
			if(isset($_current_page->ancestors) && in_array($page->ID,(array)$_current_page->ancestors)) $css_class[] = 'current_page_ancestor';
			if($page->ID == $current_page) $css_class[] = 'current_page_item';
			elseif($_current_page && $page->ID == $_current_page->post_parent) $css_class[] = 'current_page_parent';
		}
		elseif($page->ID == get_option('page_for_posts')) $css_class[] = 'current_page_parent';
		if($args['has_children'] && (integer)$depth < 1) $css_class[] = 'dropdown';
		$css_class = implode(' ',apply_filters('page_css_class',$css_class,$page,$depth,$args,$current_page));
		$output .= $indent . '<li class="' . $css_class . '"><a href="' . get_permalink($page->ID) . '">' . $link_before . apply_filters('the_title',$page->post_title,$page->ID );
		if($args['has_children'] && (integer)$depth < 1) $output .= $indent . '<b data-toggle="dropdown" class="caret"></b>';
		$output .= $link_after . '</a>';
	}
} ?>