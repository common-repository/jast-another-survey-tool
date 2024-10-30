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
 * Plugin class. This class used to do the post work for public plugin work.
 */
class lxt_jast_post {
	protected $meta = null;
	protected $ver;
	protected $slug;
	protected $plugin;

	public function __construct() {
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();
	
		add_action( 'init', array( $this, 'add_plugin_posttype' ) );

		if (! is_admin()) {
			add_action( 'the_posts', array( $this, 'check_post') );
		}
		add_filter('the_content', array( $this, 'rm_wpautop' ), 9);
		$this->meta = array('class'	=> __('Popup window class', $this->slug), 
					'visibility'	=> __('Survey visibility', $this->slug), 
					'linktext'		=> __('Popup link text', $this->slug),
					'linkclass'		=> __('Popup link class', $this->slug),
					'closeclass'	=> __('Popup close button class', $this->slug),
					'perpage'		=> __('Survey results per page', $this->slug),
					'wpautop'		=> __('Need auto p', $this->slug));
	}

	public function get_post_meta() {
		return $this->meta;
	}

	public function add_plugin_posttype() {
		$this->plugin->get_pub_obj()->add_plugin_posttype();
	}

	function check_post( $posts ) {
		if ( empty($posts) )
			return $posts;

		$shortcodes = $this->plugin->get_shortcodes();
		foreach ($posts as $post) {
			foreach ($shortcodes as $shortcode) {
				preg_match ('/\['.$shortcode.'[\s\]]/', $post->post_content, $pat_array);

				if ( is_array($pat_array) && !empty($pat_array) && count($pat_array[0]) > 0 ) {
					do_action($this->slug . '_post_has_shortcode', $shortcode);
				}
			}

			if (is_single() && get_post_type($post) == $this->slug){
				do_action($this->slug . '_post_type', array('post_type' => $this->slug, 'post_id' => $post->ID));
			}
		}

		return $posts;
	}

	function rm_wpautop($content) {
		global $post;
		// Get the keys and values of the custom fields:
		if (!isset($post->ID))
			return $content;

		$rmwpautop = get_post_meta($post->ID, $this->slug . '_md_wpautop', true);
	    // Remove the filter
		remove_filter('the_content', 'wpautop');
	    if ('false' === $rmwpautop) {
		} else {
			add_filter('the_content', 'wpautop');
	    }
		return $content;
	}
}

