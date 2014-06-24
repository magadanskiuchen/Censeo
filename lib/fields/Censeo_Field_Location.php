<?php
/**
 * Class for storing geo coordinates
 * 
 * @since 0.1
 * @see Censeo_Options
 * @see Censeo_Field
 */
Class Censeo_Field_Location extends Censeo_Field {
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
	protected $classes = array('censeo-field', 'censeo-field-location');
	
	/**
	 * The latitude value
	 * 
	 * @since 0.1
	 * @access protected
	 * @var float
	 */
	protected $lat = 0;
	
	/**
	 * The longitude value
	 * 
	 * @since 0.1
	 * @access protected
	 * @var float
	 */
	protected $lng = 0;
	
	/**
	 * Getter for the lat property
	 * 
	 * @since 0.1
	 * @access public
	 * @return float
	 */
	public function get_lat() {
		return (float)$this->lat;
	}
	
	/**
	 * Setter for the lat property
	 * 
	 * @since 0.1
	 * @access public
	 * @param float $lat
	 * @return void
	 */
	public function set_lat($lat) {
		$this->lat = (float)$lat;
	}
	
	/**
	 * Getter for the lng property
	 * 
	 * @since 0.1
	 * @access public
	 * @return float
	 */
	public function get_lng() {
		return (float)$this->lng;
	}
	
	/**
	 * Setter for the lng property
	 * 
	 * @since 0.1
	 * @access public
	 * @param float $lng
	 * @return void
	 */
	public function set_lng($lng) {
		$this->lng = (float)$lng;
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
		
		$this->value['lat'] = $this->get_lat();
		$this->value['lng'] = $this->get_lng();
		
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
		$attributes['type'] = 'hidden';
		
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
		if (isset($values['lat'])) {
			$this->set_lat($values['lat']);
		}
		
		if (isset($values['lng'])) {
			$this->set_lng($values['lng']);
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
		
		$lat_attributes = $attributes;
		$lat_attributes['id'] .= '_lat';
		$lat_attributes['name'] .= '[lat]';
		$lat_attributes['value'] = $this->get_lat();
		
		$lng_attributes = $attributes;
		$lng_attributes['id'] .= '_lng';
		$lng_attributes['name'] .= '[lng]';
		$lng_attributes['value'] = $this->get_lng();
		
		$map_container_attributes = array(
			'id' => $this->get_name() . '_map',
			'class' => 'censeo-location-map-container',
		);
		
		return '<input ' . $this->get_attr_markup($lat_attributes) . ' /><input ' . $this->get_attr_markup($lng_attributes) . ' /><div ' . $this->get_attr_markup($map_container_attributes) . '></div>';
	}
}
?>