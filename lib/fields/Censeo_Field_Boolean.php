<?php
/**
 * Class for storing a simple true or false
 * 
 * @since 0.1
 * @see Censeo_Options
 * @see Censeo_Field
 */
Class Censeo_Field_Boolean extends Censeo_Field {
	/**
	 * Classes for the field
	 * 
	 * The classes are applied to the markup tag representing the field.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$classes
	 * @var array
	 */
	protected $classes = array('censeo-field', 'censeo-field-boolean');
	
	/**
	 * Field render variant
	 * 
	 * The variant in which to render the boolean field. Valid options are "select", "radio" and "checkbox". Options are present as constants for <code>Censeo_Field_Boolean_Render_Variant</code> class.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field_Boolean_Render_Variant
	 * @var string
	 */
	protected $render_variant = Censeo_Field_Boolean_Render_Variant::SELECT;
	
	/**
	 * Getter for the value of the field
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::$value
	 * @see Censeo_Field::set_value()
	 * @see Censeo_Field::load_value()
	 * @return boolean $value The <code>$value</code> property of the field
	 */
	public function get_value() {
		return (bool)$this->value;
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
	 * Validator called when a value is set for the field
	 * 
	 * @since 0.1
	 * @access public
	 * @param mixed $value A value to be checked if it is a valid one for this field.
	 * @see Censeo_Field::validate()
	 * @return mixed The validated value that will be assigned to the field
	 */
	public function validate($values) {
		return (bool)$values;
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
		$markup = '';
		
		switch ($this->render_variant) {
			case Censeo_Field_Boolean_Render_Variant::CHECKBOX:
				$this->add_class('variant-checkbox');
				
				$markup = '<label><input type="checkbox" name="' . esc_attr($this->get_name()) . '" value="1" ' . ($this->get_value() ? 'checked="checked"' : '') . ' /> ' . esc_html($this->label) . '</label>';
				
				break;
			case Censeo_Field_Boolean_Render_Variant::RADIO:
				$this->add_class('variant-radio');
				
				$markup = '<ul class="' . esc_attr(implode(' ', $this->get_classes())) . '">';
				$markup .= '<li><label><input type="radio" name="' . esc_attr($this->get_name()) . '" value="0"' . ($this->get_value() ? '' : ' checked="checked"') . ' /> ' . __('No', 'censeo') . '</label></li>';
				$markup .= '<li><label><input type="radio" name="' . esc_attr($this->get_name()) . '" value="1"' . ($this->get_value() ? ' checked="checked"' : '') . ' /> ' . __('Yes', 'censeo') . '</label></li>';
				$markup .= '</ul>';
				
				break;
			case Censeo_Field_Boolean_Render_Variant::SELECT:
			default:
				$this->add_class('variant-select');
				
				$attributes = $this->get_attributes();
				
				$markup = '<select ' . $this->get_attr_markup($attributes) . '>';
				$markup .= '<option value="0"' . ($this->get_value() ? '' : ' selected="selected"') . '>' . __('No', 'censeo') . '</option>';
				$markup .= '<option value="1"' . ($this->get_value() ? ' selected="selected"' : '') . '>' . __('Yes', 'censeo') . '</option>';
				$markup .= '</select>';
				
				break;
		}
		
		return $markup;
	}
	
	/**
	 * Renders a wrapper and the label for the field
	 * 
	 * Calls the <code>render_field()</code> method for the actual field markup
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Boolean::render_field()
	 * @return string The full HTML markup for the container, label and the field itself
	 */
	public function render() {
		$output = parent::render();
		
		if ($this->get_render_variant() == Censeo_Field_Boolean_Render_Variant::CHECKBOX) {
			$output = '<div class="censeo-field-row no-label">' . $this->render_field() . '</div>';
		}
		
		return $output;
	}
}

/**
 * Class holder for valid options for Censeo_Field_Multiple render variants
 * 
 * @since 0.1
 * @see Censeo_Field_Multiple
 */
class Censeo_Field_Boolean_Render_Variant {
	/**
	 * SELECT variant
	 * 
	 * If this variant is applied the field will be rendered in the form of a <code><select></code> dropdown control.
	 */
	const SELECT = 'select';
	
	/**
	 * RADIO variant
	 * 
	 * If this variant is applied the field will be rendered in the form of a <code><radio></code> controls.
	 */
	const RADIO = 'radio';
	
	/**
	 * CHECKBOX variant
	 * 
	 * If this variant is applied the field will be rednered in the form of a checkbox.
	 */
	const CHECKBOX = 'checkbox';
}
?>