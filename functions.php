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
 * @since 0.1
 * @return void
 */
function censeo_after_setup_theme() {
	require_once(CENSEO_LIB . 'default-widgets.php');
	
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
 * @since 0.1
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
 * @since 0.1
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
 * @since 0.1
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
 * @since 0.1
 * @return void
 */
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
 * Helper function to return a list of localized JS strings
 * 
 * @since 0.1
 * @return array
 */
function censeo_get_fields_localization() { # TODO: eternalize function into separate file
	return array(
		'sunday' => __('Sunday', 'censeo'),
		'monday' => __('Monday', 'censeo'),
		'tuesday' => __('Tuesday', 'censeo'),
		'wednesday' => __('Wednesday', 'censeo'),
		'thursday' => __('Thursday', 'censeo'),
		'friday' => __('Friday', 'censeo'),
		'saturday' => __('Saturday', 'censeo'),
		
		'su' => _x('Su', 'min_days', 'censeo'),
		'mo' => _x('Mo', 'min_days', 'censeo'),
		'tu' => _x('Tu', 'min_days', 'censeo'),
		'we' => _x('We', 'min_days', 'censeo'),
		'th' => _x('Th', 'min_days', 'censeo'),
		'fr' => _x('Fr', 'min_days', 'censeo'),
		'sa' => _x('Sa', 'min_days', 'censeo'),
		
		'sun' => _x('Sun', 'short_days', 'censeo'),
		'mon' => _x('Mon', 'short_days', 'censeo'),
		'tue' => _x('Tue', 'short_days', 'censeo'),
		'wed' => _x('Wed', 'short_days', 'censeo'),
		'thu' => _x('Thu', 'short_days', 'censeo'),
		'fri' => _x('Fri', 'short_days', 'censeo'),
		'sat' => _x('Sat', 'short_days', 'censeo'),
		
		'january' => __('January', 'censeo'),
		'february' => __('February', 'censeo'),
		'march' => __('March', 'censeo'),
		'april' => __('April', 'censeo'),
		'may' => __('May', 'censeo'),
		'june' => __('June', 'censeo'),
		'july' => __('July', 'censeo'),
		'august' => __('August', 'censeo'),
		'september' => __('September', 'censeo'),
		'october' => __('October', 'censeo'),
		'november' => __('November', 'censeo'),
		'december' => __('December', 'censeo'),
		
		'jan' => _x('Jan', 'short_months', 'censeo'),
		'feb' => _x('Feb', 'short_months', 'censeo'),
		'mar' => _x('Mar', 'short_months', 'censeo'),
		'apr' => _x('Apr', 'short_months', 'censeo'),
		'may' => _x('May', 'short_months', 'censeo'),
		'jun' => _x('Jun', 'short_months', 'censeo'),
		'jul' => _x('Jul', 'short_months', 'censeo'),
		'aug' => _x('Aug', 'short_months', 'censeo'),
		'sep' => _x('Sep', 'short_months', 'censeo'),
		'oct' => _x('Oct', 'short_months', 'censeo'),
		'nov' => _x('Nov', 'short_months', 'censeo'),
		'dec' => _x('Dec', 'short_months', 'censeo'),
		
		'hourText' => _x('Hour', 'timepicker', 'censeo'),
		'minuteText' => _x('Minute', 'timepicker', 'censeo'),
		'secondText' => _x('Second', 'timepicker', 'censeo'),
		'millisecText' => _x('Millisecond', 'timepicker', 'censeo'),
		'microsecText' => _x('Microsecond', 'timepicker', 'censeo'),
		'timezoneText' => _x('Timezone', 'timepicker', 'censeo'),
		'AM' => _x('AM', 'timepicker', 'censeo'),
		'A' => _x('A', 'timepicker', 'censeo'),
		'PM' => _x('PM', 'timepicker', 'censeo'),
		'P' => _x('P', 'timepicker', 'censeo'),
		'closeText' => _x('Close', 'timepicker', 'censeo'),
		'currentText' => _x('Now', 'timepicker', 'censeo'),
		'timeOnlyTitle' => _x('Choose Time', 'timepicker', 'censeo'),
		'timeText' => _x('Time', 'timepicker', 'censeo'),
	);
}

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
		if (is_page()) {
			$label = get_the_title();
		} else {
			$categories = wp_get_post_categories(get_the_ID());
			
			if (!empty($categories)) {
				$first_cat = get_term($categories[0], 'category');
				
				$label = $first_cat->name;
			}
		}
	} else if (is_front_page()) {
		$label = get_the_title(get_option('page_on_front'));
	} else if (is_home()) {
		$label = get_the_title(get_option('page_for_posts'));
	} else if (is_404()) {
		$label = __('404', 'censeo');
	} else if (is_search()) {
		$label = __('Search', 'censeo');
	}
	
	$label = apply_filters('censeo_heading', $label);
	
	return $label;
}

?>