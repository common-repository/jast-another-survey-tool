<?php
/**
 * Just another survey tool.
 *
 * @package   lxt_jast
 * @author    isurgeli@gmail.com
 * @license   GPL-2.0+
 * @link      http://isurge.wordpress.com
 * @copyright 2013 Li xintao
 */

/**
 * Plugin class. This class used to do the load script and css work for public plugin work.
 */

class lxt_jast_load {
	protected $ver;
	protected $slug;
	protected $plugin;

	public function __construct() {
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();

		if (! is_admin()) {
			add_action( $this->slug . '_post_has_shortcode', array( $this, 'has_shortcode_enqueue') );	
			add_action( $this->slug . '_has_widget', array( $this, 'has_widget_enqueue') );	
			add_action( $this->slug . '_post_type', array( $this, 'post_type_enqueue') );	
		}
	}

	public function has_shortcode_enqueue( $shortcode ) {
		$shortcodes = $this->plugin->get_shortcodes(); 
		if ( $shortcode == $shortcodes[0] )
			$this->show_survey_enqueue();
		else if ( $shortcode == $shortcodes[1] )
			$this->show_result_enqueue();
	}

	public function has_widget_enqueue( $widgetid ) {
		if ( $widgetid == $this->slug.'wgsv' ) {
			$this->show_survey_enqueue();
		}
	}

	public function post_type_enqueue( $postInfo ) {
		extract( $postInfo );
		if ( $post_type == $this->slug ) {
			$this->show_survey_enqueue();
			wp_localize_script( $this->slug . '-plugin-script', 'lxt_jast_post_const', array(
				'post_id' => $post_id
			));
		}
	}

	public function show_survey_enqueue() {
		//wp_enqueue_style( $this->slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), $this->ver );
		//wp_enqueue_style( 'pure', plugins_url( 'assets/css/pure-min.css', __FILE__ ), array(), '0.3.0' );

		wp_enqueue_script( 'jquery-bPopup', plugins_url( 'assets/js/jquery.bpopup.min.js', __FILE__ ),  array( 'jquery' ), '0.9.4' );
		wp_enqueue_script( $this->slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), $this->ver );	
		$this->plugin->get_pub_obj()->localize_script_const( $this->slug . '-plugin-script' );	
	}

	public function show_result_enqueue() {
		//wp_enqueue_style( $this->slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), $this->ver );

		wp_enqueue_script( 'highcharts', plugins_url( 'assets/js/highcharts.js', __FILE__ ),  array( 'jquery' ), '1.0.9' );
		wp_enqueue_script( $this->slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), $this->ver );	
		$this->plugin->get_pub_obj()->localize_script_const( $this->slug . '-plugin-script' );
	}
}
