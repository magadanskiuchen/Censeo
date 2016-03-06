<?php
class Censeo_Pagination {
	public $query = false;
	
	public $wrapper = '<%1$s %2$s>%3$s</%1$s>';
	public $wrapper_tag = 'ul';
	public $wrapper_attributes = array('class' => 'censeo-pagination');
	public $element = '<%1$s %2$s>%3$s</%1$s>';
	public $element_tag = 'li';
	public $element_attributes = array('class' => 'censeo-pagination-elemet');
	
	public $first = true;
	public $last = true;
	public $previous = false;
	public $next = false;
	public $adjacent = 3;
	public $big_adjacent = 5;
	
	public function __construct($args = array()) {
		$args = wp_parse_args($args, self::get_defaults());
		
		foreach ($args as $prop => $value) {
			if (property_exists(get_called_class(), $prop)) {
				$reflection = new ReflectionProperty(get_called_class(), $prop);
				
				if ($reflection->isPublic()) {
					$this->$prop = $value;
				}
			}
		}
		
		if (!$this->query instanceof WP_Query) {
			$this->query = $GLOBALS['wp_query'];
		}
	}
	
	public static function get_defaults() {
		return get_class_vars(get_called_class());
	}
	
	public function get_page() {
		return max($this->query->get('paged'), 1);
	}
	
	public function get_max_page() {
		return $this->query->max_num_pages;
	}
	
	public function get_attr_markup(array $attr) {
		$markup = '';
		
		foreach ($attr as $attr_name => $attr_val) {
			$markup .= ' ' . $attr_name . '="' . esc_attr($attr_val) . '"';
		}
		
		return $markup;
	}
	
	public function get_element_markup($element) {
		$markup = '';
		$link = '';
		$attributes = $this->element_attributes;
		
		switch ($element) {
			case 'first':
				if ($this->first && $this->get_page() > 1) {
					$link = '<a href="' . get_pagenum_link(1) . '">' . __('First', 'censeo') . '</a>';
					$attributes['class'] .= ' first';
				}
				break;
			case 'last':
				if ($this->last && $this->get_page() < $this->get_max_page()) {
					$link = '<a href="' . get_pagenum_link($this->get_max_page()) . '">' . __('Last', 'censeo') . '</a>';
					$attributes['class'] .= ' last';
				}
				break;
			case 'previous':
				if ($this->previous && $this->get_page() > 1) {
					$link = '<a href="' . get_pagenum_link($this->get_page() - 1) . '">' . __('Previous', 'censeo') . '</a>';
					$attributes['class'] .= ' previous';
				}
				break;
			case 'next':
				if ($this->next && $this->get_page() < $this->get_max_page()) {
					$link = '<a href="' . get_pagenum_link($this->get_page() + 1) . '">' . __('Next', 'censeo') . '</a>';
					$attributes['class'] .= ' next';
				}
				break;
		}
		
		if (!empty($link)) {
			$attributes = apply_filters('censeo_pagination_element_markup_attributes', $attributes, $element);
			$markup = sprintf($this->element, $this->element_tag, $this->get_attr_markup($attributes), $link);
		}
		
		return $markup;
	}
	
	public function get_output() {
		$output = '';
		$attributes = $this->wrapper_attributes;
		
		if ($this->get_max_page() > 0) {
			$pagination_markup = '';
			$pagination_markup .= $this->get_element_markup('first');
			$pagination_markup .= $this->get_element_markup('previous');
			$pagination_markup .= $this->get_element_markup('current');
			$pagination_markup .= $this->get_element_markup('next');
			$pagination_markup .= $this->get_element_markup('last');
			
			$attributes = apply_filters('censeo_pagination_output_attributes', $attributes);
			$output = sprintf($this->wrapper, $this->wrapper_tag, get_attr_markup($attributes), $pagination_markup);
		}
		
		return $output;
	}
}
?>