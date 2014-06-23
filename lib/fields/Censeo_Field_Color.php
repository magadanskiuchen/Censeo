<?php
/**
 * Class for fields with color value
 * 
 * @since 0.1
 * @see Censeo_Options
 * @see Censeo_Field
 */
Class Censeo_Field_Color extends Censeo_Field {
	/**
	 * Classes for the field
	 * 
	 * The classes are applied to the markup tag representing the field.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$classes
	 * @var array
	 */
	protected $classes = array('censeo-field', 'censeo-field-color');
	
	/**
	 * Indicates whether the field is allowed to have an empty value (to be transparent)
	 * 
	 * @since 0.1
	 * @access protected
	 * @var boolean
	 */
	protected $allow_transparency = false;
	
	/**
	 * Getter for transparency
	 * 
	 * @since 0.1
	 * @access public
	 * @return boolean
	 */
	public function get_allow_transparency() {
		return $this->allow_transparency;
	}
	
	/**
	 * Setter for transparency
	 * 
	 * @since 0.1
	 * @access public
	 * @param boolean $allow_transparency
	 * @return void
	 */
	public function set_allow_transparency($allow_transparency) {
		$this->allow_transparency = (boolean)$allow_transparency;
	}
	
	/**
	 * Validator called when a value is set for the field
	 * 
	 * @since 0.1
	 * @access public
	 * @param float $value The value that should be validated to be assined to the field
	 * @see Censeo_Field::validate()
	 * @return mixed The validated value that will be assigned to the field
	 */
	public function validate($value) {
		if (!isset($_POST[$this->get_name() . '_allow_transparency']) && preg_match('/(#[0-9a-fA-F]{6})/', $value, $color)) {
			$value = $color[1];
		} else {
			$value = '';
		}
		
		return strtoupper($value);
	}
	
	/**
	 * Returns the render HTML attributes in a form of associative array
	 * 
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::get_attributes()
	 * @return array
	 */
	protected function get_attributes() {
		$attributes = parent::get_attributes();
		$attributes['type'] = 'color';
		
		return $attributes;
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
		
		$field = '<input ' . $this->get_attr_markup($attributes) . ' />';
		
		if ($this->get_allow_transparency()) {
			$transparency_field_attributes = array(
				'id' => $attributes['id'] . '-allow-transparency',
				'name' => $attributes['name'] . '_allow_transparency',
				'type' => 'checkbox',
				'value' => 1,
			);
			
			if ($this->get_value() == '') {
				$transparency_field_attributes['checked'] = 'checked';
			}
			
			$transparency_field = '<input ' . $this->get_attr_markup($transparency_field_attributes) . ' />';
			
			$field .= '<label class="no-label">' . $transparency_field . ' ' . __('Transparent', 'censeo') . '</label>';
		}
		
		return $field;
	}
}
?>