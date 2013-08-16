<?php
/**
 * Censeo admin panel theme options
 * 
 * @package Censeo
 * @subpackage Pages
 * @subpackage Options
 */

require_once(CENSEO_LIB . 'Censeo_Page.php');

/**
 * Class used for addtion theme options admin panel pages.
 * 
 * Extends Censeo_Page
 * @since 0.1
 */
class Censeo_Options extends Censeo_Page {
	protected $fields = array();
	
	public function add_field(Censeo_Field $field) {
		$this->fields[] = $field;
	}
	
	public function render() {
		parent::render();
		do_action('censeo_options_' . $this->get_id() . '_before_render');
		
		echo '<h2>' . $this->title . '</h2>';
		foreach ($this->fields as $field) {
			$field->render();
		}
		
		do_action('censeo_options_' . $this->get_id() . '_after_render');
	}
}
?>