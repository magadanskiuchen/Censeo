<?php
# TODO: add documentation

require_once(CENSEO_LIB . 'Censeo_Page.php');
require_once(CENSEO_LIB . 'Censeo_Fields.php');

class Censeo_Post_Meta {
	protected $id;
	protected $title;
	protected $post_type;
	protected $post_id;
	
	protected $fields = array();
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_title() {
		return $this->title;
	}
	
	public function get_post_type() {
		return $this->post_type;
	}
	
	public function get_post_id() {
		return $this->post_id;
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
	
	public function user_can_update() {
		$user_can_update = true;
		
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			$user_can_update = false;
		}
		
		if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $this->post_id)) {
				$user_can_update = false;
			}
		} else {
			if (!current_user_can('edit_post', $this->post_id)) {
				$user_can_update = false;
			}
		}
		
		return $user_can_update;
	}
	
	public function __construct($id, $title, $post_type = 'post') {
		$this->id = sanitize_title_with_dashes($id);
		$this->title = $title;
		$this->post_type = $post_type;
		
		add_action('add_meta_boxes', array(&$this, 'init'));
		add_action('save_post', array(&$this, 'save_field_values'), 999);
		
		add_action('censeo_post_meta_' . $this->get_id() . '_render', array(&$this, 'render_meta_fields'));
		add_action('censeo_post_meta_' . $this->get_id() . '_before_fields_render', array(&$this, 'load_field_values'));
		
		// If the action is removed then custom implementation of a nonce field should be added
		// in order to avoid errors from the check_admin_referer() call
		add_action('censeo_post_meta_' . $this->get_id() . '_hidden_fields', array(&$this, 'nonce'));
	}
	
	public function init() {
		do_action('censeo_post_meta_before_init');
		do_action('censeo_post_meta_' . $this->get_post_type() . '_before_init');
		do_action('censeo_post_meta_' . $this->get_id() . '_before_init');
		
		add_meta_box($this->get_id(), $this->get_title(), array(&$this, 'render'), $this->get_post_type());
		
		do_action('censeo_post_meta_after_init');
		do_action('censeo_post_meta_' . $this->get_post_type() . '_after_init');
		do_action('censeo_post_meta_' . $this->get_id() . '_after_init');
	}
	
	public function nonce() {
		wp_nonce_field($this->get_nonce_action(), $this->get_nonce_name());
	}
	
	public function get_nonce_action() {
		return apply_filters('censeo_post_meta_nonce_action', 'save_' . $this->get_id() . '_meta', $this->get_id());
	}
	
	public function get_nonce_name() {
		return apply_filters('censeo_post_meta_nonce_name', $this->get_id() . '_nonce', $this->get_id());
	}
	
	public function load_field_values() {
		foreach ($this->fields as &$field) {
			$field->set_value(get_post_meta($this->post_id, $field->get_name(), true));
		}
	}
	
	public function render($post) {
		$this->post_id = $post->ID;
		
		do_action('censeo_post_meta_render');
		do_action('censeo_post_meta_' . $this->get_post_type() . '_render');
		do_action('censeo_post_meta_' . $this->get_id() . '_render');
	}
	
	public function save_field_values($post_id) {
		$this->post_id = $post_id;
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_admin_referer($this->get_nonce_action(), $this->get_nonce_name())) {
			if ($this->user_can_update()) {
				foreach ($this->fields as &$field) {
					$field->load_value();
					
					$key = $field->get_name();
					$value = $field->get_value();
					
					update_post_meta($this->get_post_id(), $key, $value);
					
					if ($field->get_multiple_values() && !is_scalar($value)) {
						foreach ($value as $prop => $prop_value) {
							update_post_meta($this->get_post_id(), $key . '_' . $prop, $prop_value);
						}
					}
				}
				
				// wp_redirect(add_query_arg('censeo-updated', 1));
				// exit;
			}
		}
	}
	
	public function render_meta_fields() {
		do_action('censeo_post_meta_before_fields_render');
		do_action('censeo_post_meta_' . $this->get_post_type() . '_before_fields_render');
		do_action('censeo_post_meta_' . $this->get_id() . '_before_fields_render');
		
		?>
		<div class="censeo-form censeo-post-meta-form">
			<?php
			foreach ($this->fields as $field) {
				echo $field->render();
			}
			?>
			
			<div class="censeo-field-row">
				<?php
				do_action('censeo_post_meta_hidden_fields');
				do_action('censeo_post_meta_' . $this->get_post_type() . '_hidden_fields');
				do_action('censeo_post_meta_' . $this->get_id() . '_hidden_fields');
				?>
			</div>
		</form>
		<?php
		
		do_action('censeo_post_meta_after_fields_render');
		do_action('censeo_post_meta_' . $this->get_post_type() . '_after_fields_render');
		do_action('censeo_post_meta_' . $this->get_id() . '_after_fields_render');
	}
}
?>