<?php
/**
 * Class for building pagination links
 * 
 * Cendeo_Pagination provides basic functionality to allow you to create custom numeric pagination
 * for listing pages. There are several properties that affect the generated markup as well as
 * settings to determine what type of links should be included in the pagination.
 * 
 * @since 0.2 beta
 * 
 * @package Censeo
 * @subpackage Pagination
 */
class Censeo_Pagination {
	/**
	 * WP_Query for pagination
	 * 
	 * Reference to the query to add pagination from. The current and max pages for the pagination
	 * will be pulled as properties for this.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var WP_Query
	 */
	public $query = false;
	
	/**
	 * Structure of the wrapper for the whole pagination
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var string
	 */
	public $wrapper = '<%1$s %2$s>%3$s</%1$s>';
	
	/**
	 * HTML tag for the wrapper
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var string
	 */
	public $wrapper_tag = 'ul';
	
	/**
	 * Custom attributes for the wrapper
	 * 
	 * Pass those in the form of an associative array. The key would be the attribute name
	 * and the value would be set as the attribute value.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var array
	 */
	public $wrapper_attributes = array('class' => 'censeo-pagination');
	
	/**
	 * Markup structure for each element of the pagination
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var string
	 */
	public $element = '<%1$s %2$s>%3$s</%1$s>';
	
	/**
	 * Element tag
	 * 
	 * This will be the tag used for each element of the pagination, wrapping the anchor tag.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var string
	 */
	public $element_tag = 'li';
	
	/**
	 * Custom attributes for the elements
	 * 
	 * Pass those in the form of an associative array. THe key would be the attribute name
	 * and the value would be set as the attribute value.
	 * 
	 * @since 0.2 beta
	 * @access public
	 * @var array
	 */
	public $element_attributes = array('class' => 'censeo-pagination-elemet');
	
	/**
	 * Whether to show a "first" link
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var boolean
	 */
	public $first = true;
	
	/**
	 * Whether to show a "last" link
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var boolean
	 */
	public $last = true;
	
	/**
	 * Whether to show a "previous" link
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var boolean
	 */
	public $previous = false;
	
	/**
	 * Whether to show a "next" link
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var boolean
	 */
	public $next = false;
	
	/**
	 * Whether to show the "current" item
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var boolean
	 */
	public $current = true;
	
	/**
	 * Whether to show adjacent links and how many
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var integer
	 */
	public $adjacent = 3;
	
	/**
	 * Whether to show big adjacent links and through what step
	 * 
	 * "Big Adjacent" links are considered ones that are key to too lengthy navigations.
	 * Each of those is multiple of the value of this property.
	 * 
	 * If the <code>big_adjacent</code> links property is set to 10 and there are a total
	 * of 100 pages in the navigation, when on the first page, you'll see links for
	 * page 10, 20, 30, etc until 100.
	 * 
	 * Setting <code>big_adjacent</code> to 0 will not display those at all.
	 * Setting it to 1 will essentially show absolutely all numbered links.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var integer
	 */
	public $big_adjacent = 10;
	
	/**
	 * Whether to show ellipsis between "small" and "big" adjacent links
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @var boolean
	 */
	public $ellipsis = true;
	
	/**
	 * Denotes whether the pagination is for a single paginated article based on the global `$multipage` variable.
	 * 
	 * Applies only to paginated single posts.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access private
	 * @var boolean
	 */
	private $single_paginated = false;
	
	/**
	 * Constructor for the pagination class
	 * 
	 * You can pass an argument as an associative array to provide a value for any public
	 * property available for the class. This can be used as a shorthand to not calling
	 * <code>$this->property = $value</code> for any default property you'd like to override.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @param array $args Associative array to be set for any public property for the class instance
	 * @return Censeo_Pagination
	 */
	public function __construct($args = array()) {
		global $multipage;
		
		$args = wp_parse_args($args, self::get_defaults());
		
		foreach ($args as $prop => $value) {
			$this->set_property_if_exists($prop, $value);
		}
		
		if (!$this->query instanceof WP_Query) {
			$this->query = $GLOBALS['wp_query'];
		}
		
		$this->single_paginated = apply_filters('censeo_pagination_is_single_paginated', ($multipage && is_single()));
		
		add_filter('censeo_pagination_element_markup_attributes', array(&$this, 'element_markup_attributes'), 10, 2);
	}
	
	/**
	 * Internal method to update the value of a property when one is passed within the constructor arguments
	 * 
	 * @since 0.2 beta
	 * 
	 * @access private
	 * @param string $prop  The property to be set
	 * @param mixed  $value The value that should be assigned to the property
	 */
	private function set_property_if_exists($prop, $value) {
		if (property_exists(get_called_class(), $prop)) {
			$reflection = new ReflectionProperty(get_called_class(), $prop);
			
			if ($reflection->isPublic()) {
				$this->$prop = $value;
			}
		}
	}
	
	/**
	 * Method to provide an associative array of all public properties of the class with their default values
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @return array
	 */
	public static function get_defaults() {
		return get_class_vars(get_called_class());
	}
	
	/**
	 * Returns the current page
	 * 
	 * Shorthand method to return the current page for the pagination query.
	 * The method makes sure to return 1 instead of the default 0, used on first page.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @return integer The current page
	 */
	public function get_page() {
		$page = 1;
		
		if ($this->single_paginated) { // paginated single article
			global $page;
		} else { // listing page
			$page = max($this->query->get('paged'), 1);
		}
		
		return $page;
	}
	
	/**
	 * Returns the last page's number
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @return integer The last page's number
	 */
	public function get_max_page() {
		$max_page = 1;
		
		if ($this->single_paginated) { // paginated single article
			global $numpages;
			
			$max_page = $numpages;
		} else { // listing page
			$max_page = $this->query->max_num_pages;
		}
		
		return $max_page;
	}
	
	/**
	 * Helper method to convert an associative array into HTML tag attribute-value pairs string
	 * 
	 * @since 0.2 beta
	 * 
	 * @param  array  $attr An associative array of the attributes to be set.
	 * @return string       A ready-to-use string of attributes for an HTML tag
	 */
	public function get_attr_markup(array $attr) {
		$markup = '';
		
		foreach ($attr as $attr_name => $attr_val) {
			$markup .= ' ' . $attr_name . '="' . esc_attr($attr_val) . '"';
		}
		
		return $markup;
	}
	
	/**
	 * Internal function to check whether a certain type of element should be included in the pagination
	 * 
	 * @since 0.2 beta
	 * 
	 * @access private
	 * @param  string $element The type of element to check. Possible values are 'first', 'last', 'previous', 'next' and 'current'. Any other will return true.
	 * @return boolean         Whether the type of element should be shown in the pagination
	 */
	private function pass_conditions($element) {
		$pass = false;
		
		if ($element === 'first') {
			$pass = $this->first && $this->get_page() > 1;
		} else if ($element === 'last') {
			$pass = $this->last && $this->get_page() < $this->get_max_page();
		} else if ($element === 'previous') {
			$pass = $this->previous && $this->get_page() > 1;
		} else if ($element === 'next') {
			$pass = $this->next && $this->get_page() < $this->get_max_page();
		} else if ($element === 'current') {
			$pass = $this->current;
		} else {
			$pass = true;
		}
		
		return $pass;
	}
	
	/**
	 * Helper function that returns the HTML markup for the anchor link within an element
	 * 
	 * @since 0.2 beta
	 * @param  string $element The type of element
	 * @return string          The anchor tag to be wrapped in an element container
	 */
	public function get_link($element) {
		$url = '';
		
		switch ($element) {
			case 'first':
				$label = __('First', 'censeo');
				$url = $this->get_pagenum_link(1);
				break;
			case 'last':
				$label = __('Last', 'censeo');
				$url = $this->get_pagenum_link($this->get_max_page());
				break;
			case 'previous':
				$label = __('Previous', 'censeo');
				$url = $this->get_pagenum_link($this->get_page() - 1);
				break;
			case 'next':
				$label = __('Next', 'censeo');
				$url = $this->get_pagenum_link($this->get_page() + 1);
				break;
			case 'current':
				$label = $this->get_page();
				break;
			case '...':
				$label = '...';
				break;
			default:
				$label = $element;
				$url = $this->get_pagenum_link($element);
				break;
		}
		
		/**
		 * Allows overriding of the label for a pagination element
		 * 
		 * The filter is dynamic and should be used in the form 'censeo_pagination_label_{$element}'
		 * 
		 * @since 0.2 beta
		 * @param string $label The default label for the element
		 */
		$label = apply_filters('censeo_pagination_label_' . $element, $label);
		
		if ($url) {
			$link = '<a href="' . $url . '">' . $label . '</a>';
		} else {
			$link = '<a>' . $label . '</a>';
		}
		
		return $link;
	}
	
	/**
	 * Helper function got retrieving link to specific page number.
	 * 
	 * The function handles cases where the link should be to a page from a listing page
	 * or a page from a single post that has been paginated.
	 * 
	 * @since 0.2 beta
	 * @param  integer $pagenum The page you'd like to get link to
	 * @return string           The URL to the page
	 */
	public function get_pagenum_link($pagenum) {
		$link = '';
		
		if ($this->single_paginated) { // paginated single article
			$anchor = _wp_link_page($pagenum);
			$link = preg_replace('/<a href="([^"]*)">/', '$1', $anchor);
		} else { // listing page
			$link = get_pagenum_link($pagenum);
		}
		
		return $link;
	}
	
	/**
	 * Helper function to add more attributes/classes to the element markup
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @param  array  $attributes An associative array of the original attributes
	 * @param  string $element    The element type the attributes will be applied to
	 * @return array              An associative array that will be converted to HTML attributes
	 */
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
		} else if ($element === '...') {
			$attributes['class'] .= ' ellipsis';
		}
		
		return $attributes;
	}
	
	/**
	 * Returns the HTML representation of a pagination element
	 * 
	 * @since 0.2 beta
	 * 
	 * @param  string $element The element type
	 * @return string          The full HTML markup for a pagination element
	 */
	public function get_element_markup($element) {
		$link = '';
		$markup = '';
		
		if (is_numeric($element) && $element == $this->get_page()) { // special case where element is current element
			$markup = $this->get_element_markup('current');
		} else { // non-special case
			if ($this->pass_conditions($element)) {
				$link = $this->get_link($element);
			}
			
			if (!empty($link)) {
				/**
				 * Allows overriding of the attributes that will be added to the element
				 * 
				 * @since 0.2 beta
				 * @param array  $element_attributes The default attributes for the element
				 * @param string $element The type of element itself
				 */
				$attributes = apply_filters('censeo_pagination_element_markup_attributes', $this->element_attributes, $element);
				$markup = sprintf($this->element, $this->element_tag, $this->get_attr_markup($attributes), $link);
			}
		}
		
		return $markup;
	}
	
	/**
	 * Returns a list of numeric items for adjacent links
	 * 
	 * The result is in the form of an associative array with values for <code>before</code>
	 * and <code>after</code>, respectively whether the links are before and after the
	 * current page element.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @return array Two groups of adjacent links -- one for links coming before the current page and a group for links coming after it
	 * @see self::$adjacent
	 * @see self::get_big_adjacent()
	 */
	public function get_adjacent() {
		$before = array();
		$after = array();
		
		if ($this->adjacent) {
			$min_adjacent = max(1, $this->get_page() - $this->adjacent);
			$max_adjacent = min($this->get_page() + $this->adjacent, $this->get_max_page());
			
			for ($i = $min_adjacent; $i <= $max_adjacent; $i++) {
				if ($i < $this->get_page()) {
					$before[$i] = $i;
				} else if ($i > $this->get_page()) {
					$after[$i] = $i;
				}
			}
		}
		
		return array('before' => $before, 'after' => $after);
	}
	
	/**
	 * Returns a list of numeric items for big adjacent links
	 * 
	 * The result is in the form os an associative array with values for <code>before</code>
	 * and <code>after</code>, respectively whethe rthe links are before and after the
	 * current page element.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @return array Two groups of big adjacent links -- one for links coming before the current page and a group for links coming after it
	 * @see self::$big_adjacent
	 * @see self::get_adjacent()
	 */
	public function get_big_adjacent() {
		$before = array();
		$after = array();
		
		if ($this->big_adjacent) {
			for ($i = $this->big_adjacent; $i < $this->get_max_page(); $i += $this->big_adjacent) {
				if ($i < $this->get_page()) {
					$before[$i] = $i;
				} else if ($i > $this->get_page()) {
					$after[$i] = $i;
				}
			}
		}
		
		return array('before' => $before, 'after' => $after);
	}
	
	/**
	 * Returns the markup for pagination for a listing page
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @return string The markup for the pagination
	 * @see self::get_output()
	 */
	public function get_listing_links() {
		$output = '';
		
		if ($this->get_max_page() > 0) {
			$pagination_markup = '';
			
			$big_adjacent = $this->get_big_adjacent();
			$small_adjacent = $this->get_adjacent();
			
			$adjacent_before = $big_adjacent['before'] + $small_adjacent['before'];
			$adjacent_after = $big_adjacent['after'] + $small_adjacent['after'];
			
			ksort($adjacent_before);
			ksort($adjacent_after);
			
			$pagination_markup .= $this->get_element_markup('first');
			$pagination_markup .= $this->get_element_markup('previous');
			
			$last_output_adjacent = false;
			
			foreach ($adjacent_before as $page => $value) {
				if (
					$last_output_adjacent
					&& $this->ellipsis
					&& !in_array($last_output_adjacent, $small_adjacent['before'])
					&& in_array($page, $small_adjacent['before'])
				) {
					$pagination_markup .= $this->get_element_markup('...');
				}
				
				$pagination_markup .= $this->get_element_markup($page);
				$last_output_adjacent = $page;
			}
			
			$pagination_markup .= $this->get_element_markup('current');
			
			foreach ($adjacent_after as $page => $value) {
				if (
					$this->ellipsis
					&& in_array($last_output_adjacent, $small_adjacent['after'])
					&& !in_array($page, $small_adjacent['after'])
				) {
					$pagination_markup .= $this->get_element_markup('...');
				}
				
				$pagination_markup .= $this->get_element_markup($page);
				$last_output_adjacent = $page;
			}
			
			$pagination_markup .= $this->get_element_markup('next');
			$pagination_markup .= $this->get_element_markup('last');
			
			/**
			 * Allows overriding of the pagination wrapper attributes
			 * 
			 * @since 0.2 beta
			 * @param string $wrapper_attributes The default wrapper attributes
			 */
			$attributes = apply_filters('censeo_pagination_output_attributes', $this->wrapper_attributes);
			
			$output = sprintf($this->wrapper, $this->wrapper_tag, $this->get_attr_markup($attributes), $pagination_markup);
		}
		
		return $output;
	}
	
	/**
	 * Returns the markup for pagination for a single paginated article
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @return string The markup for the pagination
	 * @see self::get_output()
	 */
	public function get_post_single_paginated_links() {
		$output = '';
		
		if ($this->single_paginated) {
			$pagination_markup = '';
			
			$pagination_markup .= $this->get_element_markup('first');
			$pagination_markup .= $this->get_element_markup('previous');
			
			for ($i=1; $i <= $this->get_max_page(); $i++) {
				$pagination_markup .= $this->get_element_markup($i);
			}
			
			$pagination_markup .= $this->get_element_markup('next');
			$pagination_markup .= $this->get_element_markup('last');
			
			/**
			 * Allows overriding of the pagination wrapper attributes
			 * 
			 * @since 0.2 beta
			 * @param string $wrapper_attributes The default wrapper attributes
			 */
			$attributes = apply_filters('censeo_pagination_output_attributes', $this->wrapper_attributes);
			
			$output = sprintf($this->wrapper, $this->wrapper_tag, $this->get_attr_markup($attributes), $pagination_markup);
		}
		
		return $output;
	}
	
	/**
	 * A function that returns the output for the pagination
	 * 
	 * Echo the results of the function to show the pagination itself.
	 * In case you need to make adjustments to that try using the <code>wrapper</code>,
	 * <code>wrapper_tag</code>, <code>wrapper_attributes</code>, <code>element</code>,
	 * <code>element_tag</code>, <code>element_attributes</code> properties for the class
	 * as well as the filters that are available for it.
	 * 
	 * @since 0.2 beta
	 * 
	 * @access public
	 * @return string The full HTML code for the pagination
	 */
	public function get_output() {
		$output = '';
		
		if ($this->query->is_singular() && $this->single_paginated) {
			$output = $this->get_post_single_paginated_links();
		} else {
			$output = $this->get_listing_links();
		}
		
		return $output;
	}
}
?>