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
 * Plugin class. This class used to do the load script and css work for public plugin work.
 */

class lxt_jast_load_Admin {
	protected $ver;
	protected $slug;
	protected $screen_hook_suffix;
	protected $plugin;
	protected $admin;

	public function __construct() {
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->admin = lxt_jast_plugin_Admin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'common_enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'common_enqueue_admin_scripts' ) );
		//
		add_action('lxt_set_admin_screen_id', array( $this, 'set_admin_screen_id' ));
	}

	public function common_enqueue_admin_styles() {

		if ( ! isset( $this->screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), $this->ver );
		}

	}

	public function common_enqueue_admin_scripts() {

		if ( ! isset( $this->screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( 'highcharts', plugins_url( '../public/assets/js/highcharts.js', __FILE__ ),  array( 'jquery' ), '1.0.9' );
			wp_enqueue_script( $this->slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->ver );
			$this->plugin->get_pub_obj()->localize_script_const( $this->slug . '-admin-script' );	
		}
	}

	public function set_admin_screen_id($screen) {
		$this->screen_hook_suffix = $screen;
	}
}
