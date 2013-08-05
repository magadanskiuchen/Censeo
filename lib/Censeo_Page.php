<?php
/**
 * Censeo admin panel pages
 * 
 * @package Censeo
 * @subpackage Pages
 */

/**
 * Base class for adding admin panel pages
 * 
 * Calls a <code>'censeo_page_' . $this->get_id() . '_render'</code> action as render function
 * @since 0.1
 */
class Censeo_Page {
	/**
	 * Page ID
	 * 
	 * @since 0.1
	 * @access protected
	 * @var string
	 */
	protected $id;
	
	/**
	 * Page parent
	 * 
	 * @since 0.1
	 * @access protected
	 * @var string Censeo_Page::$id
	 */
	protected $parent = false;
	
	/**
	 * Page title
	 * 
	 * The label in the admin panel
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $title;
	
	/**
	 * Page review capability
	 * 
	 * The capability that is required in order to see the page
	 * @since 0.1
	 * @access public
	 * @var string WordPress capability type
	 */
	public $capability;
	
	/**
	 * Page icon
	 * 
	 * The icon will be rendered in the WordPress admin menu
	 * @since 0.1
	 * @access public
	 * @var string URL
	 */
	public $icon = '';
	
	/**
	 * Position
	 * 
	 * Relative order position in WordPress admin menu
	 * @since 0.1
	 * @access public
	 * @var int
	 */
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
		do_action('censeo_page_' . $this->get_id() . '_before_init');
		
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
		
		do_action('censeo_page_' . $this->get_id() . '_after_init');
	}
	
	public function render() {
		do_action('censeo_page_' . $this->get_id() . '_render');
	}
}
?>