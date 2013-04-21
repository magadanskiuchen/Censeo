<?php
define('CENSEO_LIB', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR);
define('CENSEO_CONFIG', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);

add_action('after_setup_theme', 'censeo_after_setup_theme');

function censeo_after_setup_theme() {
	require_once(CENSEO_LIB . 'default-widgets.php');
	require_once(CENSEO_LIB . 'options' . DIRECTORY_SEPARATOR . 'Censeo_Options.php');
	
	# i18n
	load_theme_textdomain('censeo', 'lang');
	
	# Theme support
	add_theme_support('menus');
	add_theme_support('post-thumbnails');
	
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
	add_action('widgets_init', 'censeo_widgets_init');
	add_action('wp_loaded', 'censeo_wp_loaded');
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

function censeo_widgets_init() {
	register_sidebar(array(
		'name' => __('Default Sidebar', 'censeo'),
		'id' => 'default-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
}

function censeo_wp_loaded() {
	require_once(CENSEO_CONFIG . 'options.php');
}

?>