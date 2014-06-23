<?php
/**
 * Class for fields with number value
 * 
 * @since 0.1
 * @see Censeo_Options
 * @see Censeo_Field
 */
Class Censeo_Field_Number extends Censeo_Field {
	/**
	 * Classes for the field
	 * 
	 * The classes are applied to the markup tag representing the field.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field::$classes
	 * @var array
	 */
	protected $classes = array('censeo-field', 'censeo-field-number');
	
	/**
	 * Allows you to set a minimum value for the number field
	 * 
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field_Number::$max
	 * @see Censeo_Field_Number::$step
	 * @see Censeo_Field_Number::get_min()
	 * @see Censeo_Field_Number::set_min()
	 * @var float
	 */
	protected $min = null;
	
	/**
	 * Allows you to set the maximum value for the number field
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field_Number::$min
	 * @see Censeo_Field_Number::$step
	 * @see Censeo_Field_Number::get_max()
	 * @see Censeo_Field_Number::set_max()
	 * @var float
	 */
	protected $max = null;
	
	/**
	 * Allows you to set the incremet for the value.
	 * 
	 * This will only have effect in the front end and in case the user uses an HTML5 compatible browser.
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Field_Number::$min
	 * @see Censeo_Field_Number::$max
	 * @see Censeo_Field_Number::get_step()
	 * @see Censeo_Field_Number::set_step()
	 * @var float
	 */
	protected $step = null;
	
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
		$value = floatval($value);
		
		if ($this->get_min() !== null) {
			$value = max($value, $this->get_min());
		}
		
		if ($this->get_max() !== null) {
			$value = min($value, $this->get_max());
		}
		
		return $value;
	}
	
	/**
	 * Minimum value getter
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Number::$min
	 * @see Censeo_Field_Number::set_min()
	 * @see Censeo_Field_Number::get_max()
	 * @see Censeo_Field_Number::set_max()
	 * @see Censeo_Field_Number::get_step()
	 * @see Censeo_Field_Number::set_step()
	 * @return float
	 */
	public function get_min() {
		return $this->min;
	}
	
	/**
	 * Minimum value setter
	 * 
	 * @since 0.1
	 * @access public
	 * @param float $min The minimum allowed value for the field
	 * @see Censeo_Field_Number::$min
	 * @see Censeo_Field_Number::get_min()
	 * @see Censeo_Field_Number::get_max()
	 * @see Censeo_Field_Number::set_max()
	 * @see Censeo_Field_Number::get_step()
	 * @see Censeo_Field_Number::set_step()
	 * @return void
	 */
	public function set_min($min) {
		$this->min = floatval($min);
	}
	
	/**
	 * Maximum value getter
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Number::$max
	 * @see Censeo_Field_Number::get_min()
	 * @see Censeo_Field_Number::set_min()
	 * @see Censeo_Field_Number::set_max()
	 * @see Censeo_Field_Number::get_step()
	 * @see Censeo_Field_Number::set_step()
	 * @return float
	 */
	public function get_max() {
		return $this->max;
	}
	
	/**
	 * Maximum value setter
	 * 
	 * @since 0.1
	 * @access public
	 * @param float $max The maximum allowed value for the field
	 * @see Censeo_Field_Number::$max
	 * @see Censeo_Field_Number::get_min()
	 * @see Censeo_Field_Number::set_min()
	 * @see Censeo_Field_Number::get_max()
	 * @see Censeo_Field_Number::get_step()
	 * @see Censeo_Field_Number::set_step()
	 * @return void
	 */
	public function set_max($max) {
		$this->max = floatval($max);
	}
	
	/**
	 * Step getter
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Field_Number::$step
	 * @see Censeo_Field_Number::get_min()
	 * @see Censeo_Field_Number::set_min()
	 * @see Censeo_Field_Number::get_max()
	 * @see Censeo_Field_Number::set_max()
	 * @see Censeo_Field_Number::set_step()
	 * @return float
	 */
	public function get_step() {
		return $this->step;
	}
	
	/**
	 * Step setter
	 * 
	 * @since 0.1
	 * @access public
	 * @param float $step The step for the field value
	 * @see Censeo_Field_Number::$step
	 * @see Censeo_Field_Number::get_min()
	 * @see Censeo_Field_Number::set_min()
	 * @see Censeo_Field_Number::get_max()
	 * @see Censeo_Field_Number::set_max()
	 * @see Censeo_Field_Number::get_step()
	 * @return void
	 */
	public function set_step($step) {
		$this->step = floatval($step);
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
		
		$attributes['type'] = 'number';
		$attributes['data-error-nan'] = __('Only numbers allowed', 'censeo');
		
		if ($this->get_min() !== null) {
			$attributes['min'] = $this->get_min();
			$attributes['data-error-min'] = sprintf(__('The minimum allowed value is %d', 'censeo'), $this->get_min());
		}
		
		if ($this->get_max() !== null) {
			$attributes['max'] = $this->get_max();
			$attributes['data-error-max'] = sprintf(__('The maximum allowed value is %d', 'censeo'), $this->get_max());
		}
		
		if ($this->get_step() !== null) {
			$attributes['step'] = $this->get_step();
		}
		
		return $attributes;
	}
}
?>