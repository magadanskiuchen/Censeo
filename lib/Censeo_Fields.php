<?php
/**
 * Censeo admin panel fields
 * 
 * @package Censeo
 * @subpackage Fields
 */

/**
 * Base class for admin panel fields
 * 
 * @since 0.1
 */
class Censeo_Field {
	protected $name;
	protected $value = '';
	protected $classes = array('censeo-field');
	protected $label;
	
	public function __construct($name, $value, $label) {
		$this->name = $name;
		$this->set_value($value);
		$this->set_label($label);
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_value() {
		return $this->value;
	}
	
	public function set_value($value) {
		$this->value = apply_filters('censeo_validate_value', array($this, 'validate'), $value);
	}
	
	public function load_value($value) {
		if (isset($_POST[$this->name])) {
			$this->set_value($_POST[$this->name]);
		}
	}
	
	public function get_label() {
		return $this->label;
	}
	
	public function set_label($label) {
		$this->label = $label;
	}
	
	public function get_classes() {
		return $this->classes;
	}
	
	public function add_class($class) {
		$this->classes = array_unique($classes[] = $class);
		
		return $this->classes;
	}
	
	public function remove_class($class) {
		if ($idx = array_search($class, $this->classes)) {
			unset($this->classes[$idx]);
		}
		
		return $this->classes;
	}
	
	public function validate($value) {
		return $value;
	}
	
	protected function render_field() {
		return '<input id="' . esc_attr($this->get_name()) . '" name="' . esc_attr($this->get_name()) . '" class="' . esc_attr(implode(' ', $this->get_classes())) . '" value="' . esc_attr($this->get_value()) . '" />';
	}
	
	public function render() {
		return '<div class="censeo-field"><label for="' . esc_attr($this->get_name()) . '">' . esc_html($this->label) . '</label>' . $this->render_field() . '</div>';
	}
}
?>