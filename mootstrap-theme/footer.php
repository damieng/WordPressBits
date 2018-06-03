<?php if (!function_exists('get_header')) exit;
wp_footer() ?>
		</div>
	</div>
</div>
<?php
global $start;
printf("<!--Page created at %s in %dms-->", date('c'), (microtime(true) - $start) * 1000); ?>
</body>
</html>