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
 * @see Censeo_Page
 * @see Censeo_Field
 * @since 0.1
 */
class Censeo_Options extends Censeo_Page {
	/**
	 * Fields for the options page
	 * 
	 * @since 0.1
	 * @access protected
	 * @see Censeo_Options::add_field()
	 * @see Censeo_Options::add_fields()
	 * @see Censeo_Options::set_fields()
	 * @var array
	 */
	protected $fields = array();
	
	/**
	 * Constructor for Censeo_Options
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Page::__construct()
	 * @param string $id An ID for the page
	 * @param string $title The title that will be shown in the menu and at the top of the page
	 * @param string $capability Optional. Default value "administrator". A WordPress user capability
	 * @param bool|string $parent Optional. Default value <code>false</code>. The ID of the parent page or false to make this a top-level page.
	 * @return Censeo_Options
	 */
	public function __construct($id, $title, $capability='administrator', $parent=false) {
		parent::__construct($id, $title, $capability, $parent);
		
		add_action('censeo_page_' . $this->get_id() . '_render', array(&$this, 'render_options'));
		add_action('censeo_options_' . $this->get_id() . '_before_render', array(&$this, 'load_field_values'));
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			add_action('wp_loaded', array(&$this, 'save_field_values'), 999);
		}
	}
	
	/**
	 * Allows you to add another field for the options page
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Options::$fields
	 * @see Censeo_Options::add_fields()
	 * @see Censeo_Options::set_fields()
	 * @param Censeo_Field $field A Censeo_Field object to be saved for this
	 * @return void
	 */
	public function add_field(Censeo_Field $field) {
		$this->fields[] = $field;
	}
	
	/**
	 * Allows you to add a set of fields for the options page
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Options::$fields
	 * @see Censeo_Options::add_field()
	 * @see Censeo_Options::set_fields()
	 * @param array $fields An array of Censeo_Field objects
	 * @return void
	 */
	public function add_fields(array $fields) {
		foreach ($fields as $field) {
			$this->add_field($field);
		}
	}
	
	/**
	 * Replaces the current fields with a set of new ones
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Options::$fields
	 * @see Censeo_Options::add_field()
	 * @see Censeo_Options::set_fields()
	 * @param array $fields An array of Censeo_Field objetcs
	 * @return void
	 */
	public function set_fields(array $fields) {
		$this->fields = array();
		
		$this->add_fields($fields);
	}
	
	/**
	 * Loads the value stored in the database for each field
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Options::$fields
	 * @return void
	 */
	public function load_field_values() {
		foreach ($this->fields as &$field) {
			$field->set_value(get_option($field->get_name()));
		}
	}
	
	/**
	 * Saves into the database the value for each field.
	 * 
	 * The method should not be called directly as it redirects and exits after completion.
	 * @since 0.1
	 * @access public
	 * @see Censeo_Options::$fields
	 * @return void
	 */
	public function save_field_values() {
		foreach ($this->fields as &$field) {
			$field->load_value();
			update_option($field->get_name(), $field->get_value());
		}
		
		wp_redirect(add_query_arg('censeo-updated', 1));
		exit;
	}
	
	/**
	 * Options page rendering function
	 * 
	 * @since 0.1
	 * @access public
	 * @see Censeo_Page::render()
	 * @return void
	 */
	public function render_options() {
		do_action('censeo_options_' . $this->get_id() . '_before_render');
		
		?>
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