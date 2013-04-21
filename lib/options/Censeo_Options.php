<?php
class Censeo_Options {
	private $id;
	private $parent = false;
	
	public $title;
	public $capability;
	public $icon = '';
	public $position = 90;
	
	public function __construct($id, $title, $capability='administrator', $parent=false) {
		$this->id = sanitize_title_with_dashes($id);
		$this->title = $title;
		$this->capability = $capability;
		$this->parent = $parent;
		
		add_action('admin_menu', array(&$this, 'init'));
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_parent() {
		return $this->parent;
	}
	
	public function init() {
		$page_title = $this->title;
		$menu_title = $this->title;
		$capability = $this->capability;
		$menu_slug = $this->id;
		$function = array(&$this, 'render');
		$icon_url = esc_url($this->icon);
		$position = absint($this->position);
		
		if ($this->parent) {
			add_submenu_page($this->parent, $page_title, $menu_title, $capability, $menu_slug, $function);
		} else {
			add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
		}
	}
	
	public function render() {
		
	}
}
?>