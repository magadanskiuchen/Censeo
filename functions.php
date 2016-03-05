<?php
/**
 * Default Censeo Functions
 * 
 * @package Censeo
 */

define('CENSEO_VERSION', '0.1');
define('CENSEO_LIB', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR);
define('CENSEO_CONFIG', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);

add_action('after_setup_theme', 'censeo_after_setup_theme');

/**
 * Setups core theme functionality
 * 
 * @since 0.1 alpha
 * @return void
 */
function censeo_after_setup_theme() {
	require_once(CENSEO_LIB . 'default-widgets.php');
	require_once(CENSEO_LIB . 'i18n.php');
	
	require_once(CENSEO_LIB . 'Censeo_Page.php');
	require_once(CENSEO_LIB . 'Censeo_Options.php');
	require_once(CENSEO_LIB . 'Censeo_Post_Meta.php');
	
	# i18n
	load_theme_textdomain('censeo', 'lang');
	
	# Theme support
	add_theme_support('menus');
	add_theme_support('post-thumbnails');
	
	# Add filters
	add_filter('wp_title', 'censeo_wp_title', 10, 2);
	
	# Add actions
	add_action('wp_enqueue_scripts', 'censeo_wp_enqueue_scripts');
	add_action('admin_enqueue_scripts', 'censeo_admin_enqueue_scripts');
	
	add_action('widgets_init', 'censeo_widgets_init');
	add_action('wp_loaded', 'censeo_wp_loaded');
}

/**
 * Setup theme front-end JS and CSS
 * 
 * @since 0.1 alpha
 * @return void
 */
function censeo_wp_enqueue_scripts() {
	# Enqueue styles
	wp_enqueue_style('censeo', get_bloginfo('template_directory') . '/style.css', array(), CENSEO_VERSION, 'all');
	
	# Enqueue scripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('censeo-support', get_bloginfo('template_directory') . '/js/support.js', array(), CENSEO_VERSION);
	wp_enqueue_script('censeo-functions', get_bloginfo('template_directory') . '/js/func.js', array('jquery', 'censeo-support'), CENSEO_VERSION);
}

/**
 * Setup theme admin panel JS and CSS
 * 
 * @since 0.1 alpha
 * @return void
 */
function censeo_admin_enqueue_scripts() {
	# Enqueue styles
	wp_enqueue_style('censeo-fields', get_bloginfo('template_directory') . '/lib/fields.css');
	
	# Enqueue scripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('censeo-support', get_bloginfo('template_directory') . '/js/support.js', array(), CENSEO_VERSION);
	wp_enqueue_script('censeo-fields', get_bloginfo('template_directory') . '/lib/fields.js', array('jquery', 'censeo-support'), CENSEO_VERSION);
	wp_localize_script('censeo-fields', 'ci18n', censeo_get_fields_localization());
}

/**
 * Set custom format of wp_title
 * 
 * @since 0.1 alpha
 * 
 * @param string $title
 * @param string $sep
 * @return string The formatted title
 */
function censeo_wp_title($title, $sep) {
	global $paged, $page;
	
	if (is_feed()) return $title;
	
	$title .= get_bloginfo('name');
	$site_description = get_bloginfo('description', 'display');
	
	if ($site_description && (is_home() || is_front_page())) $title = "$title $sep $site_description";
	
	if ($paged >= 2 || $page >= 2) $title = $title . ' ' . $sep . ' ' . sprintf(__('Page %s', 'censeo'), max($paged, $page));
	
	return $title;
}

/**
 * Register theme sidebars
 * 
 * @since 0.1 alpha
 * @return void
 */
function censeo_widgets_init() {
	/**
	 * Filter to allow you to chang the widget wrapper tag
	 * 
	 * @since 0.2 beta
	 * @param string The tag
	 */
	$widget_tag = apply_filters('censeo_widget_tag', 'div');
	
	/**
	 * Filter to allow you to chang the widget wrapper classes
	 * 
	 * @since 0.2 beta
	 * @param array An array of the classes
	 */
	$widget_classes = apply_filters('censeo_widget_classes', array('widget'));
	
	/**
	 * Filter to allow you to chang the widget heading tag
	 * 
	 * @since 0.2 beta
	 * @param string The tag
	 */
	$widget_heading_tag = apply_filters('censeo_widget_heading_tag', 'h3');
	
	/**
	 * Filter to allow you to chang the widget heading classes
	 * 
	 * @since 0.2 beta
	 * @param string An array of the classes
	 */
	$widget_heading_classes = apply_filters('censeo_widget_heading_classes', array('widget-title'));
	
	register_sidebar(array(
		'name' => __('Default Sidebar', 'censeo'),
		'id' => 'default-sidebar',
		'before_widget' => '<' . $widget_tag . ' id="%1$s" class="' . implode(' ', $widget_classes) . ' %2$s">',
		'after_widget' => '</' . $widget_tag . '>',
		'before_title' => '<' . $widget_heading_tag . ' class="' . implode(' ', $widget_heading_classes) . '">',
		'after_title' => '</' . $widget_heading_tag . '>',
	));
}

/**
 * Callback function for the <code>wp_loaded</code> action.
 * 
 * Load theme deep functionality that requires full setup
 * @since 0.1
 * @return void
 */
function censeo_wp_loaded() {
	require_once(CENSEO_CONFIG . 'options.php');
	require_once(CENSEO_CONFIG . 'post_meta.php');
}

/**
 * Helper function to get an appropriate text for a heading tag on any page
 * 
 * This will return the following in the mentioned cases:
 * - author listing page -- the author name
 * - category, tag or custom taxonomy listing page -- the name of the term
 * - post type archive -- the name of the post type
 * - any type of date archive -- the year, month and day depending on what is applicable
 * - page, blogpost or single page of a custom post type -- the title of the entry
 * - front page -- the title of the page (if a custom one is used) or the label "Home" if the front page list the latest blogposts
 * - blog listing -- the title of the page used for blog listing
 * - 404 -- simply show a "404" as label
 * - search -- a label "Search"
 * 
 * @since 0.2 beta
 * @return string A label describing the type of page being presented
 */
function censeo_heading() {
	if (is_archive()) {
		if (is_author()) {
			$label = get_the_author();
		} else if (is_category() || is_tag() || is_tax()) {
			$label = single_term_title();
		} else if (is_post_type_archive()) {
			$label = post_type_archive_title('', false);
		} else if (is_date()) {
			if (is_year()) {
				$label = get_the_time('Y');
			} else if (is_month()) {
				$label = get_the_time('M Y');
			} else if (is_day()) {
				$label = get_the_time(get_option( 'date_format' ));
			}
		}
	} else if (is_singular()) {
		$label = get_the_title();
	} else if (is_front_page()) {
		$front_page_id = get_option('page_on_front');
		
		if ($front_page_id) {
			$label = get_the_title($front_page_id);
		} else {
			$label = __('Home', 'mf');
		}
	} else if (is_home()) {
		$posts_page_id = get_option('page_for_posts');
		
		if ($posts_page_id) {
			$label = get_the_title($posts_page_id);
		} else {
			$label = __('Blog', 'mf');
		}
	} else if (is_404()) {
		$label = __('404', 'censeo');
	} else if (is_search()) {
		$label = __('Search', 'censeo');
	}
	
	/**
	 * Override the value returned by the `censeo_heading` function
	 * 
	 * @since 0.2 beta
	 * 
	 * @param string $label The label originally returned by the function
	 */
	$label = apply_filters('censeo_heading', $label);
	
	return $label;
}

?>