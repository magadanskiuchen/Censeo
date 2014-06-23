<?php
/**
 * Class to store time values
 * 
 * @since 0.1
 * @see Censeo_Options
 */
class Censeo_Field_Time extends Censeo_Field {
	/**
	 * Classes for the field
	 * 
	 * The classes are applied to the markup tag representing the field.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$classes
	 * @var array
	 */
	protected $classes = array('censeo-field', 'censeo-field-time');
	
	/**
	 * Constructor for a new time field
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $name The name of the field
	 * @param string $label The user-friendly label of the field
	 * @return Censeo_Field_Time
	 */
	public function __construct($name, $label) {
		parent::__construct($name, $label);
		
		// add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts')); # TODO: Add JS fallback for time input (https://fgelinas.com/code/timepicker/)
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
		$attributes['type'] = 'time';
		$attributes['value'] = $this->get_value();
		
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
			$pm = stripos($value, 'pm') !== false;
			
			if (preg_match('/([\d]{1,2}).([\d]{2})/iu', $value, $time)) { # TODO: test with time with hours, minutes and seconds
				$hours = $time[1];
				$minutes = $time[2];
				
				if ($pm) {
					$hours = absint($hours) + 12;
				}
				
				$hours = absint(min($hours, 23));
				$minutes = absint(min($minutes, 59));
				
				$value = self::leading_zero($hours) . ':' . self::leading_zero($minutes);
			}
		}
		
		if (!isset($value)) {
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
		# TODO add proper JS fallback
		// wp_enqueue_script('jquery-ui-datepicker');
		// wp_enqueue_style('censeo-jquery-ui-datepicker', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css', array(), '1.10.4', 'screen');
	}
	
	/**
	 * Helper function to format hours number for time with leading zero
	 * 
	 * @since 0.1
	 * @access public
	 * @param mixed $number The number you'd like to format with leading zeros
	 * @param int $digits The number of digits you'd like the returned number to have
	 * @return string A version of the $number with the necessary amount of leading zeros to match the $digits length
	 */
	public static function leading_zero($number, $digits = 2) {
		for ($i = strlen($number); $i < $digits; $i++) {
			$number = '0' . $number;
		}
		
		return $number;
	}
}
?>