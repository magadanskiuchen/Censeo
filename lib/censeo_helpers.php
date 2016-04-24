<?php
/**
 * Helper to convert an associative array into HTML tag attribute-value pairs string
 * 
 * @since 0.2 beta
 * 
 * @param  array  $attr An associative array of the attributes to be set.
 * @return string       A ready-to-use string of attributes for an HTML tag
 */
function censeo_get_attr_markup(array $attr) {
	$markup = '';
	
	foreach ($attr as $attr_name => $attr_val) {
		$markup .= ' ' . $attr_name . '="' . esc_attr($attr_val) . '"';
	}
	
	return $markup;
}

?>