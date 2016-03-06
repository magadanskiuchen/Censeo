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
	public $big_adjacent = 10;
	
	public function __construct($args = array()) {
		$args = wp_parse_args($args, self::get_defaults());
		
		foreach ($args as $prop => $value) {
			$this->set_property_if_exists($prop, $value);
		}
		
		if (!$this->query instanceof WP_Query) {
			$this->query = $GLOBALS['wp_query'];
		}
		
		add_filter('censeo_pagination_element_markup_attributes', array(&$this, 'element_markup_attributes'), 10, 2);
	}
	
	private function set_property_if_exists($prop, $value) {
		if (property_exists(get_called_class(), $prop)) {
			$reflection = new ReflectionProperty(get_called_class(), $prop);
			
			if ($reflection->isPublic()) {
				$this->$prop = $value;
			}
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
	
	public function pass_conditions($element) {
		$pass = false;
		
		if ($element === 'first') {
			$pass = $this->first && $this->get_page() > 1;
		} else if ($element === 'last') {
			$pass = $this->last && $this->get_page() < $this->get_max_page();
		} else if ($element === 'previous') {
			$pass = $this->previous && $this->get_page() > 1;
		} else if ($element === 'next') {
			$pass = $this->next && $this->get_page() < $this->get_max_page();
		} else {
			$pass = true;
		}
		
		return $pass;
	}
	
	public function get_link($element) {
		$url = '';
		
		switch ($element) {
			case 'first':
				$label = __('First', 'censeo');
				$url = get_pagenum_link(1);
				break;
			case 'last':
				$label = __('Last', 'censeo');
				$url = get_pagenum_link($this->get_max_page());
				break;
			case 'previous':
				$label = __('Previous', 'censeo');
				$url = get_pagenum_link($this->get_page() - 1);
				break;
			case 'next':
				$label = __('Next', 'censeo');
				$url = get_pagenum_link($this->get_page() + 1);
				break;
			case 'current':
				$label = $this->get_page();
				break;
			default:
				$label = $element;
				$url = get_pagenum_link($element);
				break;
		}
		
		$label = apply_filters('censeo_pagination_label_' . $element, $label);
		
		if ($url) {
			$link = '<a href="' . $url . '">' . $label . '</a>';
		} else {
			$link = '<a>' . $label . '</a>';
		}
		
		return $link;
	}
	
	public function element_markup_attributes($attributes, $element) {
		if ($element === 'first') {
			$attributes['class'] .= ' first';
		} else if ($element === 'last') {
			$attributes['class'] .= ' last';
		} else if ($element === 'previous') {
			$attributes['class'] .= ' previous';
		} else if ($element === 'next') {
			$attributes['class'] .= 'next';
		} else if ($element === 'current') {
			$attributes['class'] .= ' current';
		}
		
		return $attributes;
	}
	
	public function get_element_markup($element) {
		$link = '';
		$markup = '';
		
		if ($this->pass_conditions($element)) {
			$link = $this->get_link($element);
		}
		
		if (!empty($link)) {
			$attributes = apply_filters('censeo_pagination_element_markup_attributes', $this->element_attributes, $element);
			$markup = sprintf($this->element, $this->element_tag, $this->get_attr_markup($attributes), $link);
		}
		
		return $markup;
	}
	
	public function get_output() {
		$output = '';
		$attributes = $this->wrapper_attributes;
		
		if ($this->get_max_page() > 0) {
			$pagination_markup = '';
			
			$adjacent_before = array();
			$adjacent_after = array();
			
			if ($this->big_adjacent) {
				for ($i = $this->big_adjacent; $i < $this->get_max_page(); $i += $this->big_adjacent) {
					if ($i < $this->get_page()) {
						$adjacent_before[$i] = $i;
					} else if ($i > $this->get_page()) {
						$adjacent_after[$i] = $i;
					}
				}
			}
			
			if ($this->adjacent) {
				$min_adjacent = max(1, $this->get_page() - $this->adjacent);
				$max_adjacent = min($this->get_page() + $this->adjacent, $this->get_max_page());
				
				for ($i = $min_adjacent; $i <= $max_adjacent; $i++) {
					if ($i < $this->get_page()) {
						$adjacent_before[$i] = $i;
					} else if ($i > $this->get_page()) {
						$adjacent_after[$i] = $i;
					}
				}
			}
			
			ksort($adjacent_before);
			ksort($adjacent_after);
			
			$pagination_markup .= $this->get_element_markup('first');
			$pagination_markup .= $this->get_element_markup('previous');
			
			foreach ($adjacent_before as $page => $value) {
				$pagination_markup .= $this->get_element_markup($page);
			}
			
			$pagination_markup .= $this->get_element_markup('current');
			
			foreach ($adjacent_after as $page => $value) {
				$pagination_markup .= $this->get_element_markup($page);
			}
			
			$pagination_markup .= $this->get_element_markup('next');
			$pagination_markup .= $this->get_element_markup('last');
			
			$attributes = apply_filters('censeo_pagination_output_attributes', $attributes);
			$output = sprintf($this->wrapper, $this->wrapper_tag, $this->get_attr_markup($attributes), $pagination_markup);
		}
		
		return $output;
	}
}
?>