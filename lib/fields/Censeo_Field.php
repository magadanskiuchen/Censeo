<?php
/**
 * Base class for admin panel fields
 * 
 * @since 0.1
 * @see Censeo_Options
 */
class Censeo_Field {
	/**
	 * Name of the field
	 * 
	 * Used for setting the markup id and name attributes as well as the key for the value in the database.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::get_name()
	 * @var string
	 */
	protected $name;
	
	/**
	 * Value of the field
	 * 
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::get_value()
	 * @see Censeo_Field::set_value()
	 * @see Censeo_Field::load_value()
	 * @var mixed
	 */
	protected $value = '';
	
	/**
	 * Denotes whether the field needs to have multiple values associated with it
	 * 
	 * The values would be passed to the container in the form of an (associative) array or an object
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$value
	 * @var boolean
	 */
	protected $multiple_values = false;
	
	/**
	 * Classes for the field
	 * 
	 * The classes are applied to the markup tag representing the field.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::get_classes()
	 * @see Censeo_Field::add_class()
	 * @see Censeo_Field::remove_class()
	 * @var array
	 */
	protected $classes = array('censeo-field');
	
	/**
	 * User-friendly label of the field
	 * 
	 * This is used as the label text when rendering the field
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::get_label()
	 * @see Censeo_Field::set_label()
	 * @var string
	 */
	protected $label;
	
	/**
	 * Constructor for a new field
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $name The name of the field
	 * @param string $label The user-friendly label of the field
	 * @return Censeo_Field
	 */
	public function __construct($name, $label) {
		$this->name = $name;
		$this->set_label($label);
		
		add_filter('censeo_validate_' . $this->get_name() . '_value', array(&$this, 'validate'));
	}
	
	/**
	 * Getter for the name of the field
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::$name
	 * @return string $name The <code>$name</code> property of the field
	 */
	public function get_name() {
		return $this->name;
	}
	
	/**
	 * Getter for the value of the field
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::$value
	 * @see Censeo_Field::set_value()
	 * @see Censeo_Field::load_value()
	 * @return mixed $value The <code>$value</code> property of the field
	 */
	public function get_value() {
		return $this->value;
	}
	
	/**
	 * Setter for the value of the field
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $value The value to be assigned to the field
	 * @see Censeo_Field::$value
	 * @see Censeo_Field::get_value()
	 * @see Censeo_Field::load_value()
	 * @return void
	 */
	public function set_value($value) {
		$this->value = apply_filters('censeo_validate_' . $this->get_name() . '_value', $value, $this);
	}
	
	/**
	 * Getter for the multiple values property
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::$multiple_values
	 * @return boolean $multiple_values
	 */
	public function get_multiple_values() {
		return (boolean)$this->multiple_values;
	}
	
	/**
	 * Setter for the multiple values property
	 * 
	 * @since 0.1
	 * @access public
	 * @param boolean $multiple_values Either true of false whether the field should save multiple values in the database
	 * @see Censeo_Field::$multiple_values
	 * @return void
	 */
	public function set_multiple_values($multiple_values) {
		$this->multiple_values = (boolean)$multiple_values;
	}
	
	/**
	 * Gets a potential new value for the field in case of a POST request
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::$value
	 * @see Censeo_Field::get_value()
	 * @see Censeo_Field::set_value()
	 * @return void
	 */
	public function load_value() {
		if (isset($_POST[$this->name])) {
			$this->set_value(stripslashes_deep($_POST[$this->name]));
		}
	}
	
	/**
	 * Getter for the label of the field
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::$label
	 * @see Censeo_Field::set_label()
	 * @return string $label
	 */
	public function get_label() {
		return $this->label;
	}
	
	/**
	 * Seter for the label of the field
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $label The admin panel label that will be associated to the field
	 * @see Censeo_Field::$label
	 * @see Censeo_Field::get_label()
	 * @return void
	 */
	public function set_label($label) {
		$this->label = $label;
	}
	
	/**
	 * Getter for the field classes
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::$classes
	 * @see Censeo_Field::add_class()
	 * @see Censeo_Field::remove_class()
	 * @return array $classes The array of the HTML classes that would be added to the field markup representation
	 */
	public function get_classes() {
		return $this->classes;
	}
	
	/**
	 * Allows you to add a new class for the field rendering
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $class The class to be added to the field
	 * @see Censeo_Field::$classes
	 * @see Censeo_Field::get_classes()
	 * @see Censeo_Field::remove_class()
	 * @return array $classes The updated array of the HTML classes that would be added to the field markup representation
	 */
	public function add_class($class) {
		$this->classes[] = $class;
		$this->classes = array_unique($this->classes);
		
		return $this->classes;
	}
	
	/**
	 * Allows you to remove a new class for the field rendering
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $class The class to be searched for and removed from the field if found
	 * @see Censeo_Field::$classes
	 * @see Censeo_Field::get_classes()
	 * @see Censeo_Field::add_class()
	 * @return array $classes The updated array of the HTML classes that would be added to the field markup representation
	 */
	public function remove_class($class) {
		if ($idx = array_search($class, $this->classes)) {
			unset($this->classes[$idx]);
		}
		
		return $this->classes;
	}
	
	/**
	 * Validator called when a value is set for the field
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $value The value to be validated
	 * @see Censeo_Field::set_value()
	 * @return mixed The validated value that will be assigned to the field
	 */
	public function validate($value) {
		return $value;
	}
	
	/**
	 * Helper function to map associative array to markup attributes
	 * 
	 * Array keys are used as attribute names and values are used as attribute values.
	 * Note that the attributes will be escaped
	 * @since 0.1
	 * @access public
	 * @param array $attr An associative array with property-value pair of attributes markup should be generated for
	 * @return string HTML markup for tag attributes
	 */
	public function get_attr_markup(array $attr) {
		$markup = '';
		
		foreach ($attr as $attr_name => $attr_val) {
			$markup .= ' ' . $attr_name . '="' . esc_attr($attr_val) . '"';
		}
		
		return $markup;
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
			'type' => 'text',
			'class' => implode(' ', $this->get_classes()),
			'value' => $this->get_value(),
		);
	}
	
	/**
	 * A renderer function for the field markup itself
	 * 
	 * A wrapper markup is separately rendered by the <code>render()</code> class method
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::render()
	 * @return string The HTML markup for the field itself
	 */
	protected function render_field() {
		$attributes = $this->get_attributes();
		
		return '<input ' . $this->get_attr_markup($attributes) . ' />';
	}
	
	/**
	 * Renders a wrapper and the label for the field
	 * 
	 * Calls the <code>render_field()</code> method for the actual field markup
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field::render_field()
	 * @return string The full HTML markup for the container, label and the field itself
	 */
	public function render() {
		return '<div class="censeo-field-row"><label class="field-label" for="' . esc_attr($this->get_name()) . '">' . esc_html($this->label) . ':</label><div class="censeo-field-column">' . $this->render_field() . '</div></div>';
	}
}
?>