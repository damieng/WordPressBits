<div class="pagination pagination-centered">
<?php
global $wp_query;

$big = 999999999; // need an unlikely integer
echo paginate_links( array(
'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
'format' => '?paged=%#%',
'show_all' => False,
'end_size' => 1,
'mid_size' => 2,
'prev_next' => True,
'prev_text' => __('&laquo;'),
'next_text' => __('&raquo;'),
'current' => max( 1, get_query_var('paged') ),
'total' => $wp_query->max_num_pages,
'type' => 'list'
) );
?>
</div>