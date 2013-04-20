<?php
if (have_posts()) {
	while (have_posts()) {
		the_post();
		
		get_template_part('post', get_post_format());
	}
} else {
	get_search_form();
}
?>