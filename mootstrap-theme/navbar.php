<?php if (!function_exists('get_header')) exit; ?>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
		<ul class="nav">
			<li><a class="brand" href="/"><?php bloginfo('name')?></a>
				<ul class="children">
					<li><a href="/about/">About me</a></li>
					<li><a href="/about/responsible-disclosure">Blog details</a></li>
				</ul>
			</li>
		</ul>
		<ul class="nav centered">
		</ul>
		<form class="navbar-search pull-right form-search" action="/" method="get">
		  <div class="input-append">	
			  <input value="<?php the_search_query(); ?>" name="s" id="s" type="text" class="span2 search-query" placeholder="Search term" />
			  <button type="submit" class="btn">Go</button>
		  </div>
		</form> 
    </div>
  </div>
</div>