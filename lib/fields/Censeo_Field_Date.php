<?php
/**
 * Class to store date values
 * 
 * @since 0.1
 * @see Censeo_Options
 */
class Censeo_Field_Date extends Censeo_Field {
	/**
	 * Classes for the field
	 * 
	 * The classes are applied to the markup tag representing the field.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$classes
	 * @var array
	 */
	protected $classes = array('censeo-field', 'censeo-field-date');
	
	/**
	 * Date format
	 * 
	 * The format of the date text representation.
	 * @since 0.1
	 * @access protected
	 * @var string
	 */
	protected $format = 'Y-m-d';
	
	/**
	 * Constructor for a new date field
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $name The name of the field
	 * @param string $label The user-friendly label of the field
	 * @return Censeo_Field_Date
	 */
	public function __construct($name, $label) {
		parent::__construct($name, $label);
		
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));
	}
	
	/**
	 * Getter for the format
	 * 
	 * @since 0.1
	 * @access public
	 * @return string
	 */
	public function get_format() {
		return apply_filters('censeo_date_format', $this->format);
	}
	
	/**
	 * Setter for the format
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $format
	 * @return void
	 */
	public function set_format($format) {
		$this->format = $format;
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
		$attributes['type'] = 'date';
		
		return $attributes;
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
		if (!empty($value)) {
			$value = date('Y-m-d', strtotime($value));
		} else {
			$value = '';
		}
		
		return $value;
	}
	
	/**
	 * Enqueue scripts for JS enhancements for older browsers
	 * 
	 * @since 0.1
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('censeo-jquery-ui-datepicker', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css', array(), '1.10.4', 'screen');
	}
}
?>