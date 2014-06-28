<?php
# TODO: add documentation

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
	
	public function __construct($id, $title, $post_type = 'post') {
		$this->id = sanitize_title_with_dashes($id);
		$this->title = $title;
		$this->post_type = $post_type;
		
		add_action('add_meta_boxes', array(&$this, 'init'));
		
		add_action('censeo_post_meta_' . $this->get_id() . '_render', array(&$this, 'render_meta_fields'));
		add_action('censeo_post_meta_' . $this->get_id() . '_before_render', array(&$this, 'load_field_values'));
		
		// If the action is removed then custom implementation of a nonce field should be added
		// in order to avoid errors from the check_admin_referer() call
		add_action('censeo_post_meta_' . $this->get_id() . '_hidden_fields', array(&$this, 'nonce'));
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[$this->get_id()])) {
			add_action('wp_loaded', array(&$this, 'save_field_values'), 999);
		}
	}
	
	public function init() {
		do_action('censeo_post_meta_before_init');
		do_action('censeo_post_meta_' . $this->get_id() . '_before_init');
		
		add_meta_box($this->get_id(), $this->get_title(), array(&$this, 'render'), $this->get_post_type());
		
		do_action('censeo_post_meta_after_init');
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
	
	public function save_field_values() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_admin_referer($this->get_nonce_action(), $this->get_nonce_name())) {
			foreach ($this->fields as &$field) {
				$field->load_value();
				
				$key = $field->get_name();
				$value = $field->get_value();
				
				update_post_meta($this->get_id(), $key, $value);
				
				if ($field->get_multiple_values() && !is_scalar($value)) {
					foreach ($value as $prop => $prop_value) {
						update_post_meta($this->get_id(), $key . '_' . $prop, $prop_value);
					}
				}
			}
			
			// wp_redirect(add_query_arg('censeo-updated', 1));
			// exit;
		}
	}
}
?>