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
 * Plugin class. This class used to do the widget work for public plugin work.
 * Survey link widget.
 */
class lxt_jast_wgsv extends WP_Widget {
	protected $ver;
	protected $slug;
	protected $plugin;

	public function __construct() {
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();

		parent::__construct (
			$this->slug.'wgsv', // Base ID
			__('Survey', $this->slug), // Name
			array( 'description' => __( 'A widget can contain survey links.', $this->slug ), ) // Args
		);

		//add_action( 'init', array ( $this, 'check_widget_used' ));
	}

//	public function check_widget_used() {
//		if ( is_active_widget( false, false, $this->id_base, true ) ) {
//			do_action($this->slug . '_has_widget', $this->id_base);
//		}
//	}

	public static function register_widget() {
		register_widget( 'lxt_jast_wgsv' );
	}
 
    //build the widget settings form
    public function form($instance) {
        $defaults = array( 'title' => '' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = $instance['title'];
		echo '<p>'._e('Survey:', $this->slug).'<select name="'.$this->get_field_name( 'title' ).'" >';
		$loop = $this->plugin->get_pub_obj()->get_post_loop();
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) {
				$loop->the_post();
				echo '<option value="'.get_the_title().'" '.selected( $title, get_the_title() ).' >'.get_the_title().'</option>';
			}
		}
		echo '</select></p>';

		wp_reset_postdata();
    }
 
    //save the widget settings
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
 
        return $instance;
    }
 
    //display the widget
	function widget($args, $instance) {
		do_action($this->slug . '_has_widget', $this->id_base);

        extract($args);

		$title = apply_filters( 'widget_title', __('Survey', $this->slug) );
        $surveytitle = $instance['title'];
		
		$output = $this->plugin->get_pub_obj()->get_survey_container($surveytitle, 'widget' );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		echo $output;
		echo $after_widget;
    }
}
