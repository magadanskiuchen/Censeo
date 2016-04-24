<?php
/**
 * Class for fields with multiple enumerable values
 * 
 * @since 0.1
 * @see Censeo_Options
 * @see Censeo_Field
 */
Class Censeo_Field_Multiple extends Censeo_Field {
	/**
	 * Classes for the field
	 * 
	 * The classes are applied to the markup tag representing the field.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$classes
	 * @var array
	 */
	protected $classes = array('censeo-field', 'censeo-field-multiple');
	
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
	 * Field render variant
	 * 
	 * The variant in which to render the enumerable field. Valid options are "select" and "checkboxes". Options are present as constants for <code>Censeo_Field_Multiple_Render_Variant</code> class.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field_Multiple_Render_Variant
	 * @var string
	 */
	protected $render_variant = Censeo_Field_Multiple_Render_Variant::SELECT;
	
	/**
	 * Select size
	 * 
	 * Option is applicable only if the SELECT render variant is used.
	 * @since 0.1
	 * @access protected
	 * @var int
	 */
	protected $size = 5;
	
	/**
	 * Field options getter
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Multiple::$options
	 * @see Censeo_Field_Multiple::set_options()
	 * @see Censeo_Field_Multiple::add_options()
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
	 * @see Censeo_Field_Multiple::$options
	 * @see Censeo_Field_Multiple::get_options()
	 * @see Censeo_Field_Multiple::add_options()
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
	 * @see Censeo_Field_Multiple::$options
	 * @see Censeo_Field_Multiple::get_options()
	 * @see Censeo_Field_Multiple::set_options()
	 * @return array
	 */
	public function add_options(array $options) {
		$this->options = $this->options + $options;
	}
	
	/**
	 * Getter for render variant
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Multiple::$render_variant
	 * @return string
	 */
	public function get_render_variant() {
		return $this->render_variant;
	}
	
	/**
	 * Setter for render variant
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Multiple::$render_variant
	 * @return void
	 */
	public function set_render_variant($variant) {
		$this->render_variant = $variant;
	}
	
	/**
	 * Getter for field size
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Multiple::$size
	 * @return int
	 */
	public function get_size() {
		return $this->size;
	}
	
	/**
	 * Setter for field size
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Multiple::$size
	 * @return void
	 */
	public function set_size($size) {
		$this->size = min(2, absint($size));
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
	public function validate($values) {
		$values = (array)$values;
		
		foreach ($values as &$value) {
			// value should be 0 instead of FALSE
			if (!$value) $value = 0;
		}
		unset($value);
		
		foreach ($values as $i => $value) {
			if (!array_key_exists($value, $this->options)) {
				unset($values[$i]);
			}
		}
		
		return $values;
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
			'name' => $this->get_name() . '[]',
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
		$markup = '';
		
		switch ($this->render_variant) {
			case Censeo_Field_Multiple_Render_Variant::CHECKBOXES:
				$this->add_class('variant-checkboxes');
				$markup = '<ul class="' . esc_attr(implode(' ', $this->get_classes())) . '">';
				
				foreach ($this->options as $value => $label) {
					$markup .= '<li><label><input type="checkbox" name="' . esc_attr($this->get_name()) . '[]" value="' . esc_attr($value) . '" ' . (in_array($value, $this->get_value()) ? ' checked="checked"' : '') . ' /> ' . $label . '</label></li>';
				}
				
				$markup .= '</ul>';
				break;
			case Censeo_Field_Multiple_Render_Variant::SELECT:
			default:
				$this->add_class('variant-select');
				
				$attributes = $this->get_attributes();
				
				$markup = '<select ' . censeo_get_attr_markup($attributes) . ' multiple size="' . $this->get_size() . '">';
				
				foreach ($this->options as $value => $label) {
					$markup .= '<option value="' . esc_attr($value) . '"' . (in_array($value, $this->get_value()) ? ' selected="selected"' : '') . '>' . $label . '</option>';
				}
				
				$markup .= '</select>';
				
				break;
		}
		
		return $markup;
	}
}

/**
 * Class holder for valid options for Censeo_Field_Multiple render variants
 * 
 * @since 0.1
 * @see Censeo_Field_Multiple
 */
class Censeo_Field_Multiple_Render_Variant {
	/**
	 * SELECT variant
	 * 
	 * If this variant is applied the field will be rendered in the form of a <code><select></code> dropdown control.
	 */
	const SELECT = 'select';
	
	/**
	 * CHECKBOXES variant
	 * 
	 * If this variant is applied the field will be rednered in the form of a checkboxes buttons group.
	 */
	const CHECKBOXES = 'checkboxes';
}
?>