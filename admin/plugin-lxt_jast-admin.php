<?php
/**
 * Just another survey tool.
 *
 * @package   lxt_jast_Admin
 * @author    isurgeli@gmail.com
 * @license   GPL-2.0+
 * @link      http://isurge.wordpress.com
 * @copyright 2013 Li xintao
 */

/**
 * Plugin class. This class used do the admin init work
 */
class lxt_jast_plugin_Admin {

	protected static $instance = null;
	protected $slug = null;
	protected $ver = null;
	protected $plugin = null;

	private function __construct() { 
		self::$instance = $this;
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		require_once( plugin_dir_path( __FILE__ ) . 'load-'.$this->slug.'-admin.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'post-'.$this->slug.'-admin.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'menu-'.$this->slug.'-admin.php' );

		new lxt_jast_load_Admin();
		new lxt_jast_menu_Admin();
		new lxt_jast_post_Admin();
		
	}

	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			new self;
		}

		return self::$instance;
	}

	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url('edit.php?post_type='.$this->slug.'&page=' . $this->slug.'_results' ) . '">' . __( 'Results', $this->slug ) . '</a>'
			),
			$links
		);
	}
}
