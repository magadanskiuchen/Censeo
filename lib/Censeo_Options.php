<?php
/**
 * Censeo admin panel theme options
 * 
 * @package Censeo
 * @subpackage Pages
 * @subpackage Options
 */

require_once(CENSEO_LIB . 'Censeo_Page.php');
require_once(CENSEO_LIB . 'Censeo_Fields.php');

/**
 * Class used for addtion theme options admin panel pages.
 * 
 * Extends Censeo_Page
 * @since 0.1
 */
class Censeo_Options extends Censeo_Page {
	protected $fields = array();
	
	public function __construct($id, $title, $capability='administrator', $parent=false) {
		parent::__construct($id, $title, $capability, $parent);
		
		add_action('censeo_options_' . $this->get_id() . '_before_render', array(&$this, 'load_field_values'));
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			add_action('wp_loaded', array(&$this, 'save_field_values'), 999);
		}
	}
	
	public function add_field(Censeo_Field $field) {
		$this->fields[] = $field;
	}
	
	public function add_fields(array $fields) {
		foreach ($fields as $field) {
			$this->add_field($field);
		}
	}
	
	public function set_fields(array $fields) {
		$this->fields = array();
		
		$this->add_fields($fields);
	}
	
	public function load_field_values() {
		foreach ($this->fields as &$field) {
			$field->set_value(get_option($field->get_name()));
		}
	}
	
	public function save_field_values() {
		foreach ($this->fields as &$field) {
			$field->load_value();
			update_option($field->get_name(), $field->get_value());
		}
		
		wp_redirect(add_query_arg('censeo-updated', 1));
		exit;
	}
	
	public function render() {
		parent::render();
		do_action('censeo_options_' . $this->get_id() . '_before_render');
		
		?>
		<h2><?php echo $this->title; ?></h2>
		
		<form action="" method="post">
			<?php
			foreach ($this->fields as $field) {
				echo $field->render();
			}
			?>
			
			<div class="censeo-field-row row-submit">
				<input type="submit" class="button button-primary" value="<?php esc_attr_e('Save', 'censeo'); ?>" />
			</div>
		</form>
		<?php
		
		do_action('censeo_options_' . $this->get_id() . '_after_render');
	}
}
?>