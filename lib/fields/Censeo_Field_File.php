<?php
/**
 * Class for uploading and storing file information
 * 
 * @since 0.1
 * @see Censeo_Options
 * @see Censeo_Field
 */
Class Censeo_Field_File extends Censeo_Field {
	/**
	 * Denotes whether the field needs to have multiple values associated with it
	 * 
	 * The values would be passed to the container in the form of an (associative) array or an object
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$value
	 * @var boolean
	 */
	protected $multiple_values = true;
	
	/**
	 * Classes for the field
	 * 
	 * The classes are applied to the markup tag representing the field.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$classes
	 * @var array
	 */
	protected $classes = array('censeo-field', 'censeo-field-file');
	
	/**
	 * Constructor for a new location field
	 * 
	 * @since 0.1
	 * @access public
	 * @param string $name The name of the field
	 * @param string $label The user-friendly label of the field
	 * @return Censeo_Field_File
	 */
	public function __construct($name, $label) {
		parent::__construct($name, $label);
		
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));
	}
	
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
		$this->value = array();
		
		return $this->value;
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
		$attributes['type'] = 'file';
		
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
	public function validate($values) {
		return $values;
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
		
		$output = '<input type="hidden" name="MAX_FILE_SIZE" value="' . Censeo_Size_Formatter::to_bytes(ini_get('upload_max_filesize')) . '" />';
		$output .= '<input ' . $this->get_attr_markup($attributes) . ' />';
		$output .= '<p class="no-label">' . __('Max upload file size:', 'censeo') . ' ' . Censeo_Size_Formatter::to_megabytes(ini_get('upload_max_filesize')) . '</p>';
		
		return $output;
	}
	
	/**
	 * Enqueue scripts for JS functionality
	 * 
	 * @since 0.1
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('plupload-handlers');
	}
}

# TODO: add documentation for size formatter class
class Censeo_Size_Formatter {
	public static function to_bytes($size) {
		$multiplier = substr(rtrim(strtolower($size), 'b'), -1);
		
		switch ($multiplier) {
			case 't':
				$size *= 1024;
			case 'g':
				$size *= 1024;
			case 'm':
				$size *= 1024;
			case 'k':
				$size *= 1024;
		}
		
		return $size;
	}
	
	public static function to_kilobytes($size) {
		$bytes = self::to_bytes($size);
		
		return ($bytes / 1024) . 'k';
	}
	
	public static function to_megabytes($size) {
		$bytes = self::to_bytes($size);
		
		return ($bytes / 1024 / 1024) . 'M';
	}
	
	public static function to_gigabytes($size) {
		$bytes = self::to_bytes($size);
		
		return ($bytes / 1024 / 1024 / 1024) . 'G';
	}
	
	public static function to_terabytes($size) {
		$bytes = self::to_bytes($size);
		
		return ($bytes / 1024 / 1024 / 1024 / 1024) . 'T';
	}
}
?>