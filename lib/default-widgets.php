<?php
/**
 * Default widget functionality
 * 
 * @package Censeo
 */

/**
 * Provides an array of the theme's default widgets
 * 
 * @since 0.1
 * @return array List of function names
 */
function censeo_default_widgets() {
	return array('censeo_search_widget', 'censeo_latest_posts_widget');
}

/**
 * Helper function for formatting of widget output
 * 
 * @since 0.1
 */
function censeo_before_widget($before_widget, $id) {
	return sprintf($before_widget, 'widget_' . $id, 'widget_' . $id);
}

/**
 * Provides default search widget functionality
 * 
 * @since 0.1
 * @param array A sidebar options associative array
 */
function censeo_search_widget($options) {
	echo censeo_before_widget($options['before_widget'], 'search');
	echo $options['before_title'] . __('Search', 'censeo') . $options['after_title'];
	get_search_form();
	echo $options['after_widget'];
}

/**
 * Provides default latest posts widget functionality
 * 
 * @since 0.1
 * @param array A sidebar options associative array
 */
function censeo_latest_posts_widget($options) {
	$posts = get_posts();
	if (!empty($posts)) {
		echo censeo_before_widget($options['before_widget'], 'latest_posts');
		echo $options['before_title'] . __('Latest Posts', 'censeo') . $options['after_title'];
		
		echo '<ul>';
		foreach ($posts as $apost) {
			echo '<li><a href="' . get_permalink($apost->ID) . '" rel="bookmark">' . get_the_title($apost->ID) . '</a></li>';
		}
		echo '</ul>';
		
		echo $options['after_widget'];
	}
}

foreach (censeo_default_widgets() as $action) {
	add_action($action, $action);
}
?>