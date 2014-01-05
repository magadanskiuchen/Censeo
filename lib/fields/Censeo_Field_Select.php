<?php
/**
 * Class for fields with number value
 * 
 * @since 0.1
 * @see Censeo_Options
 * @see Censeo_Field
 */
Class Censeo_Field_Select extends Censeo_Field {
	/**
	 * Classes for the field
	 * 
	 * The classes are applied to the markup tag representing the field.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$classes
	 * @var array
	 */
	protected $classes = array('censeo-field', 'censeo-field-select');
	
	/**
	 * Options for the field
	 * 
	 * The options that are available for the user to choose from.
	 * @since 0.1
	 * @access protected
	 * @var array
	 */
	protected $options = array();
	
	/**
	 * Field options getter
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Select::$options
	 * @see Censeo_Field_Select::set_options()
	 * @see Censeo_Field_Select::add_options()
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}
	
	/**
	 * Field options setter
	 * 
	 * @since 0.1
	 * @access public
	 * @param array $options An associative array of value-label pairs that should be assigned as field options. This would replace previous value for the options.
	 * @see Censeo_Field_Select::$options
	 * @see Censeo_Field_Select::get_options()
	 * @see Censeo_Field_Select::add_options()
	 * @return array
	 */
	public function set_options(array $options) {
		$this->options = $options;
	}
	
	/**
	 * Field options adder
	 * 
	 * @since 0.1
	 * @access public
	 * @param array $options An associative array of value-label pairs that should be appended as field options. This will preserve the existing values and only append the new ones.
	 * @see Censeo_Field_Select::$options
	 * @see Censeo_Field_Select::get_options()
	 * @see Censeo_Field_Select::set_options()
	 * @return array
	 */
	public function add_options(array $options) {
		$this->options = $this->options + $options;
	}
	
	/**
	 * Validator called when a value is set for the field
	 * 
	 * @since 0.1
	 * @access public
	 * @param mixed $value A value to be checked if it is a valid one for this field.
	 * @see Censeo_Field::validate()
	 * @return mixed The validated value that will be assigned to the field
	 */
	public function validate($value) {
		if (!array_key_exists($value, $this->options)) {
			$value = key($this->options);
		}
		
		return $value;
	}
	
	/**
	 * Returns the render HTML attributes in a form of associative array
	 * 
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::get_attr_markup()
	 * @return array
	 */
	protected function get_attributes() {
		return array(
			'id' => $this->get_name(),
			'name' => $this->get_name(),
			'class' => implode(' ', $this->get_classes()),
		);
	}
	
	/**
	 * A renderer function for the field markup itself
	 * 
	 * A wrapper markup is separately rendered by the <code>render()</code> class method
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::render_field()
	 * @return string The HTML markup for the field itself
	 */
	protected function render_field() {
		$attributes = $this->get_attributes();
		
		return '<select ' . $this->get_attr_markup($attributes) . '>' . $this->render_options() . '</select>';
	}
	
	/**
	 * Returns the markup for the element options
	 * 
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field_Select::render_field()
	 * @return string The HTML markup for the field options
	 */
	protected function render_options() {
		$markup = '';
		
		foreach ($this->options as $value => $label) {
			$markup .= '<option value="' . esc_attr($value) . '"' . ($this->get_value() == $value ? ' selected="selected"' : '') . '>' . $label . '</option>';
		}
		
		return $markup;
	}
}
?>