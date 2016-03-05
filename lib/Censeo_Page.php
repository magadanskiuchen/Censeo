<?php
/**
 * Censeo admin panel pages
 * 
 * The class provides an easy-to-use options to add admin panel menu and sub-menu pages.
 * By default a new instance of the class will be added as a menu page.
 * In order to add it as a sub-menu page you need to set a parent.
 * Set the sub-menu's parent property to the ID of the parent page.
 * 
 * If the parent page is not added via the Censeo framework, set the ID to the menu page's <code>menu_slug</code>.
 * 
 * Use the <code>attach_template</code> method to pass the name of a template part
 * that would be used for rendering the content on the admin panel page.
 * 
 * There are several hooks (including dynamic ones) to affect the pages' functionality:
 * <ul>
 * 	<li>censeo_page_before_init</li>
 * 	<li>censeo_page_{$id}_before_init</li>
 * 	<li>censeo_page_after_init</li>
 * 	<li>censeo_page_{$id}_after_init</li>
 * 	<li>censeo_page_render</li>
 * 	<li>censeo_page_{$id}_render</li>
 * </ul>
 * 
 * @since 0.1 alpha
 * 
 * @package Censeo
 * @subpackage Pages
 */

/**
 * Base class for adding admin panel pages
 * 
 * Calls a <code>'censeo_page_' . $this->get_id() . '_render'</code> action as render function
 * 
 * @since 0.1 alpha
 */
class Censeo_Page {
	/**
	 * Page ID
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access protected
	 * @var string
	 */
	protected $id;
	
	/**
	 * Page parent
	 * 
	 * This can only be set through the constructor method.
	 * Pass the value for the <code>$id</code> you've used when constructing the instance to use
	 * as parent or the <code>menu_slug</code> you've used for custom <code>add_menu_page</code>
	 * calls that have been placed.
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access protected
	 * @var string Censeo_Page::$id
	 */
	protected $parent = false;
	
	/**
	 * Render template
	 * 
	 * The template part to be used when renderin the page.
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access protected
	 * @var string
	 */
	protected $template = '';
	
	/**
	 * Page title
	 * 
	 * The label in the admin panel
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @var string
	 */
	public $title;
	
	/**
	 * Page review capability
	 * 
	 * The capability that is required in order to see the page
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @var string WordPress capability type
	 */
	public $capability;
	
	/**
	 * Page icon
	 * 
	 * The icon will be rendered in the WordPress admin menu
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @var string URL
	 */
	public $icon = '';
	
	/**
	 * Position
	 * 
	 * Relative order position in WordPress admin menu alpha
	 * 
	 * @since 0.1
	 * @access public
	 * @var int
	 */
	public $position = 90;
	
	/**
	 * Constructor for Censeo_Page
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @param string $id An ID for the page
	 * @param string $title The title that will be shown in the menu and at the top of the page
	 * @param string $capability Optional. Default value "administrator". A WordPress user capability
	 * @param bool|string $parent Optional. Default value <code>false</code>. The ID of the parent page or false to make this a top-level page.
	 * @return Censeo_Page
	 */
	public function __construct($id, $title, $capability='administrator', $parent=false) {
		$this->id = sanitize_title_with_dashes($id);
		$this->title = $title;
		$this->capability = $capability;
		$this->parent = $parent;
		
		add_action('admin_menu', array(&$this, 'init'));
	}
	
	/**
	 * Returns the page ID
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @see Censeo_Page::$id
	 * @return string The page ID
	 */
	public function get_id() {
		return $this->id;
	}
	
	/**
	 * Returns the page title
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @see Censeo_Page::$parent
	 * @return string The page title
	 */
	public function get_parent() {
		return $this->parent;
	}
	
	/**
	 * Callback function for the <code>admin_menu</code> action
	 * 
	 * The function uses the <code>censeo_page_before_init</code>, <code>censeo_page_{$id}_before_init</code>,
	 * <code>censeo_page_after_init</code> and <code>censeo_page_{$id}_after_init</code> action hooks.
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @return void
	 */
	public function init() {
		/**
		 * Before init action hook
		 * 
		 * You can either use the generic <code>censeo_page_before_init</code> hook
		 * or the specialized <code>censeo_page_{$id}_before_init</code> one.
		 * 
		 * @since 0.1 alpha
		 */
		do_action('censeo_page_before_init');
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
		
		/**
		 * After init action hook
		 * 
		 * You can either use the generic <code>censeo_page_after_init</code> hook
		 * or the specialized <code>censeo_page_{$id}_after_init</code> one.
		 * 
		 * @since 0.1 alpha
		 */
		do_action('censeo_page_after_init');
		do_action('censeo_page_' . $this->get_id() . '_after_init');
	}
	
	/**
	 * Page rendering function
	 * 
	 * The function uses the <code>censeo_page_render</code> and <code>censeo_page_{$id}_render</code>
	 * action hooks. The internals of the <code>Censeo_Page</code> class rely on these actions.
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @return void
	 */
	public function render() {
		?>
		<div class="wrap">
			<h2><?php echo $this->title; ?></h2>
			
			<?php
			do_action('censeo_page_render');
			do_action('censeo_page_' . $this->get_id() . '_render');
			?>
		</div>
		<?php
	}
	
	/**
	 * Set a template for page rendering
	 * 
	 * Provides a way to attach a template that will be used to render the page.
	 * The class' <code>render_template_part</code> is called upon the <code>censeo_page_{$id}_render</code> hook.
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @param string $template_part The path to the file that should be used as template. Should be provided in a <code>get_template_part()</code> compatible manner
	 * @return void
	 * @see Censeo_Page::render_template_part()
	 */
	public function attach_template($template_part) {
		$this->template = $template_part;
		
		add_action('censeo_page_' . $this->get_id() . '_render', array(&$this, 'render_template_part'));
	}
	
	/**
	 * Loads the associated template
	 * 
	 * @since 0.1 alpha
	 * 
	 * @access public
	 * @return void
	 * @see Censeo_Page::attach_template()
	 * @uses get_template_part
	 */
	public function render_template_part() {
		get_template_part($this->template);
	}
}
?>