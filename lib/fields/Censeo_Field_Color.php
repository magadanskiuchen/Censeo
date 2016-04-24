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
	 * Default value
	 * 
	 * @since 0.1
	 * @access protected
	 * @var string
	 */
	protected $default_value = '#FFFFFF';
	
	/**
	 * Constructor for a new color field
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $name The name of the field
	 * @param string $label The user-friendly label of the field
	 * @return Censeo_Field_Color
	 */
	public function __construct($name, $label) {
		parent::__construct($name, $label);
		
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));
	}
	
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
	 * Getter for default value
	 * 
	 * @since 0.1
	 * @access public
	 * @return string
	 */
	public function get_default_value() {
		return $this->default_value;
	}
	
	/**
	 * Setter for default value
	 * 
	 * @since 0.1
	 * @access public
	 * @param boolean $default_value
	 * @return void
	 */
	public function set_default_value($default_value) {
		$this->default_value = $default_value;
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
		if (!isset($_POST[$this->get_name() . '_allow_transparency']) && preg_match('/(#[0-9a-fA-F]{6})/', $value, $color)) { # TODO: make consistent with location and file fields
			$value = $color[1];
		} else {
			$value = $this->get_allow_transparency() ? '' : $this->get_default_value();
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
		$attributes['data-default-color'] = $this->get_default_value();
		
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
		
		$field = '<input ' . censeo_get_attr_markup($attributes) . ' />';
		
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
			
			$transparency_field = '<input ' . censeo_get_attr_markup($transparency_field_attributes) . ' />';
			
			$field .= '<label class="alternative-action">' . $transparency_field . ' ' . __('Transparent', 'censeo') . '</label>';
		}
		
		return $field;
	}
	
	/**
	 * Enqueue scripts for JS enhancements for older browsers
	 * 
	 * @since 0.1
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style('wp-color-picker');
	}
}
?>