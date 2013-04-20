<?php
add_action('after_setup_theme', 'censeo_after_setup_theme');

function censeo_after_setup_theme() {
	# i18n
	load_theme_textdomain('censeo', 'lang');
	
	# Enqueue styles
	if (!is_admin()) {
		wp_enqueue_style('censeo', get_bloginfo('template_directory') . '/style.css', array(), '0.1', 'all');
	}
	
	# Enqueue scripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('censeo-functions', get_bloginfo('template_directory') . '/js/func.js', array('jquery'), '0.1');
	
	# Add filters
	add_filter('wp_title', 'censeo_wp_title', 10, 2);
	
	# Add actions
}

function censeo_wp_title($title, $sep) {
	global $paged, $page;
	
	if (is_feed()) return $title;
	
	$title .= get_bloginfo('name');
	$site_description = get_bloginfo('description', 'display');
	
	if ($site_description && (is_home() || is_front_page())) $title = "$title $sep $site_description";
	
	if ($paged >= 2 || $page >= 2) $title = $title . ' ' . $sep . ' ' . sprintf(__('Page %s', 'censeo'), max($paged, $page));
	
	return $title;
}
?>