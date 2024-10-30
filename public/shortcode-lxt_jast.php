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
 * Plugin class. This class used to do the shortcode parse work for public plugin work.
 */
class lxt_jast_shortcode {
	protected $shortcodes = array('lxt_jast_survey', 'lxt_jast_result', 'lxt_jast_qust', 'lxt_jast_submit', 'lxt_jast_email');

	protected $ver;
	protected $slug;
	protected $plugin;

	public function __construct() {
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();
		add_action( 'init', array( $this, 'add_plugin_shortcode' ) );
	}

	public function get_shortcodes() {
		return $this->shortcodes;
	}

	public function add_plugin_shortcode() {
		foreach ($this->shortcodes as $shortcode) {
			add_shortcode( $shortcode, array( $this, $shortcode) );
		}
	}

	public function lxt_jast_result($attr) {
		$attr = shortcode_atts( array(
			'title' => null,
			'name' => null,
			'type' => null
		), $attr );

		return $this->plugin->get_pub_obj()->get_survey_chart_frame($attr);
	}

	public function lxt_jast_qust($attr, $content) {
		extract( shortcode_atts( array(
			'name' => null,
			'type' => 'text',
			'class' => $this->slug.'_qust',
			'option' => null,
			'content' => $this->get_label_content( $content ),
			'otherclass' => $this->slug.'_other'
		), $attr ) );

		if ($attr['name'] == null) 
			return __('Error,a question must have a name', $this->slug);

		if (array_key_exists($attr['type'], $this->plugin->get_pub_obj()->get_option_type()) && !$attr['option']) 
			return __('Error, no option answer.', $this->slug);

		$this->plugin->get_pub_obj()->add_class( $attr['class'], $this->slug . '_qust'); 

		ob_start();
	
		if ($type == 'textarea') { ?>
			<div class="<?php echo $class; ?>"><?php echo $content; ?><textarea name="<?php echo $name; ?>"></textarea></div> <?php
		} else if ($type == 'radio' || $type == 'checkbox') { 
			$options = explode(";", $option); ?>
			<div class="<?php echo $class; ?>"> <?php	
			echo $content; 
			foreach ($options as $item) { ?>
				<span><input type="<?php echo $type; ?>" name="<?php echo $name; ?>" value="<?php echo $item; ?>"/> <?php
				if ($item) { ?>
					<label><?php echo $item; ?></label> <?php
				}else{ ?>
					<input type="text" class=<?php echo $otherclass; ?> name="<?php echo $name; ?>~OTHER" /> <?php
				} ?>
				</span> <?php
			} ?>
			</div> <?php
		} else if ($type == 'select') { 
			$options = explode(";", $option); ?>
			<div class="<?php echo $class; ?>"> <?php	
			echo $content; ?> 
			<select name="<?php echo $name; ?>"> <?php
			foreach ($options as $item) { ?>
				<option value="<?php echo $item; ?>"><?php echo $item; ?></option> <?php
			} ?>
			</select></div> <?php
		} else { ?>
			<div class="<?php echo $class; ?>"><?php echo $content; ?><input type="<?php echo $type; ?>" name="<?php echo $name; ?>" /></div> <?php
		}

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function lxt_jast_email($attr, $content) {
		extract( shortcode_atts( array(
			'class' => $this->slug . '_qust',
			'name' => $this->slug . '_the_email',
			'content' => $this->get_label_content( $content )
		), $attr ) );

		$this->plugin->get_pub_obj()->add_class( $class, $this->slug . '_qust'); 

		$output = '';

		if ( !is_user_logged_in() ) {
			$output = "<div class=\"{$class}\">{$content}<input type=\"email\" name=\"{$name}\" /></div>";
		}

		return $output;
	}

	private function get_label_content( $content ) {
		if ( $content != strip_tags($content) )
			return $content;
		else
			return '<h2>' . $content . '</h2>';
	}

	public function lxt_jast_submit($attr) {
		extract( shortcode_atts( array(
			'value' => __('Finish' , $this->slug),
			'class' => $this->slug . '_submit',
			'afterurl' => 'back',
			'message' => ''
		), $attr ) );

		$this->plugin->get_pub_obj()->add_class( $class, $this->slug . '_submit'); 

		$output = "<div class=\"{$class}\" url=\"{$afterurl}\" message=\"{$message}\"><button type=\"button\">{$value}</button></div>";

		return $output;
	}


	public function lxt_jast_survey($attr) {
		if (!$attr || !($title = $attr['title'])) 
			return __('Must indicate the survey title', $this->slug);

		return $this->plugin->get_pub_obj()->get_survey_container( $title, 'shortcode' );
	}
}
