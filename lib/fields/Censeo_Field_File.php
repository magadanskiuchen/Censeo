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
	 * The URL value
	 * 
	 * @since 0.1
	 * @access protected
	 * @var string
	 */
	protected $url = '';
	
	/**
	 * The attachment ID value
	 * 
	 * @since 0.1
	 * @access protected
	 * @var int
	 */
	protected $attachment_id = 0;
	
	/**
	 * Allowed types for the uploaded file
	 * 
	 * @since 0.1
	 * @access protected
	 * @var string
	 */
	protected $file_type = '';
	
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
	 * Getter for the URL property
	 * 
	 * @since 0.1
	 * @access public
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}
	
	/**
	 * Setter for the URL property
	 * 
	 * @since 0.1
	 * @access public
	 * @return void
	 */
	public function set_url($url) {
		$this->url = $url;
	}
	
	/**
	 * Getter for the attachment id property
	 * 
	 * @since 0.1
	 * @access public
	 * @return int
	 */
	public function get_attachment_id() {
		return absint($this->attachment_id);
	}
	
	/**
	 * Setter for the attachment id property
	 * 
	 * @since 0.1
	 * @access public
	 * @return void
	 */
	public function set_attachment_id($id) {
		$this->attachment_id = absint($id);
	}
	
	/**
	 * Getter for the file type property
	 * 
	 * @since 0.1
	 * @access public
	 * @return int
	 */
	public function get_file_type() {
		return $this->file_type;
	}
	
	/**
	 * Setter for the file type property
	 * 
	 * @since 0.1
	 * @access public
	 * @return void
	 */
	public function set_file_type($type) {
		$this->file_type = $type;
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
		
		$this->value['url'] = $this->get_url();
		$this->value['attachment_id'] = $this->get_attachment_id();
		
		return $this->value;
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
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (!function_exists('wp_handle_upload')) {
				require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php');
			}
			
			if (!function_exists('wp_generate_attachment_metadata')) {
				require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'image.php');
			}
			
			$file = $_FILES[$this->get_name()];
			
			if ($file['error'] == 0) {
				$file_data = wp_handle_upload($file, array('test_form'=>false));
				
				if (!isset($file_data['error'])) {
					$attachment = array(
						'post_mime_type' => $file_data['type'],
						'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_data['file'])),
						'post_content' => '',
						'post_status' => 'inherit'
					);
					
					$attachment_id = wp_insert_attachment($attachment, $file_data['file'], 0);
					
					if (strpos($file_data['type'], 'image') !== false) {
						$attachment_data = wp_generate_attachment_metadata($attachment_id, $file_data['file']);
						wp_update_attachment_metadata($attachment_id, $attachment_data);
					}
					
					$this->attachment_id = $attachment_id;
					$this->url = $file_data['url'];
				}
			} else {
				if (isset($_POST[$this->name])) {
					$this->set_value(stripslashes_deep($_POST[$this->name]));
				}
			}
		}
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
		$attributes['data-button-label'] = __('Select File', 'censeo');
		$attributes['data-window-label'] = __('Files', 'censeo');
		$attributes['data-value-type'] = __('Value Type', 'censeo');
		$attributes['data-file-type'] = $this->get_file_type();
		
		if (isset($attributes['value'])) {
			unset($attributes['value']);
		}
		
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
		if (isset($values['attachment_id'])) {
			$this->set_attachment_id($values['attachment_id']);
		}
		
		if (isset($values['url'])) {
			$this->set_url($values['url']);
		}
		
		return $this->get_value();
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
		
		if ($this->get_attachment_id()) {
			$output .= wp_get_attachment_image($this->get_attachment_id(), 'thumbnail', 1, array('class'=>'no-label censeo-file-preview'));
			
			$url_field_attributes = array('type'=>'hidden', 'name'=>$this->get_name() . '[url]', 'value'=>$this->get_url());
			$attachment_id_field_attributes = array('type'=>'hidden', 'name'=>$this->get_name() . '[attachment_id]', 'value'=>$this->get_attachment_id());
			
			$output .= '<input ' . $this->get_attr_markup($url_field_attributes) . ' />';
			$output .= '<input ' . $this->get_attr_markup($attachment_id_field_attributes) . ' />';
		}
		
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
		wp_enqueue_media();
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