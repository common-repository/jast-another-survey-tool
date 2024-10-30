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
 * Plugin class. This class used to do the post work for public plugin work.
 */
class lxt_jast_post_Admin {
	protected $ver;
	protected $slug;
	protected $plugin;
	protected $admin;

	public function __construct() {
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->admin = lxt_jast_plugin_Admin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();

		add_action( 'add_meta_boxes', array ( $this, 'add_post_type_metabox') );
		add_action( 'save_post', array ( $this, $this->slug.'_save_meta') );
	}

	public function add_post_type_metabox() {
		//create a custom meta box
		add_meta_box( 'lxt_meta_box', 
			__('Survey property'), 
			array( $this, $this->slug.'_posttype_metabox' ), 
			$this->slug,
			'normal', 
			'default' );
	}

	public function lxt_jast_posttype_metabox( $post ) {
		$survey_meta = $this->plugin->get_survey_meta();
		$attr = $this->plugin->get_pub_obj()->get_survey_meta_data( $post->ID ); 
		extract( shortcode_atts( array(
				'visibility' => __('All', $this->slug),
				'class' => '',
				'linktext' => '',
				'linkclass' => '',
				'closeclass' => '',
				'perpage' => 5,
				'wpautop' => 'false'
			), $attr ) );
		?>
		<p>
		<div style="display: table;">
		<?php foreach(array(array('visibility', 'linktext', 'perpage', 'wpautop'), array('class', 'linkclass', 'closeclass')) as $group) {?>
			<div style="display: table-row;">
			<?php foreach($group as $item) {?>
				<label style="display: table-cell"><?php echo esc_attr__( $survey_meta[$item], $this->slug); ?></label>
				<?php if ($item != 'visibility' && $item != 'wpautop') {?>
					<input style="display: table-cell;width: 95%" type="text" name="<?php echo $this->slug ?>_md_field_<?php echo $item ?>" value="<?php echo esc_attr( $$item ); ?>"/>
				<?php } else if ($item == 'visibility') { ?>
					<select style="display: table-cell;width: 95%" name="<?php echo $this->slug ?>_md_field_visibility">
						<option value="All" <?php selected( $visibility, 'All' ); ?>>
							<?php echo esc_attr__( 'All', $this->slug); ?>
						</option>
						<option value="Login user" <?php selected( $visibility, 'Login user' ); ?>>
							<?php echo esc_attr__( 'Login user', $this->slug); ?>
						</option>
					</select>
				<?php } else {?>
					<select style="display: table-cell;width: 95%" name="<?php echo $this->slug ?>_md_field_wpautop">
						<option value="true" <?php selected( $wpautop, 'true' ); ?>>
							<?php echo esc_attr__( 'Yes', $this->slug); ?>
						</option>
						<option value="false" <?php selected( $wpautop, 'false' ); ?>>
							<?php echo esc_attr__( 'No', $this->slug); ?>
						</option>
					</select>
				<?php } ?>
			<?php } ?>
			</div>
		<?php } ?>
		</div>
    <?php
 
	}

	public function lxt_jast_save_meta( $post_id ) {
		//verify the metadata is set
		if ( isset( $_POST[$this->slug.'_md_field_visibility'] ) ) {
			$survey_meta = $this->plugin->get_survey_meta();
			foreach($survey_meta as $key => $value) 
				update_post_meta( $post_id, $this->slug . '_md_' . $key, strip_tags( $_POST[$this->slug.'_md_field_' . $key] ) );
		}
	}
}

