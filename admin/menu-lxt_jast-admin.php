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
class lxt_jast_menu_Admin {
	protected $ver;
	protected $slug;
	protected $plugin;
	protected $admin;

	public function __construct() {
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->admin = lxt_jast_plugin_Admin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();

		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		require_once( plugin_dir_path( __FILE__ ) . 'views/' . $this->slug . '_option_page.php' );
		new lxt_jast_option_page();
	}

	public function add_plugin_admin_menu() {

		$screen_hook_suffix = add_submenu_page( 
			'edit.php?post_type='.$this->slug, 
			__( 'View survey results', $this->slug ),
			__( 'Survey results', $this->slug ),
			'manage_options',
			$this->slug.'_results',
			array( $this, 'display_survey_result_page' )
		);
		
		do_action('lxt_set_admin_screen_id', $screen_hook_suffix);
	}

	public function display_survey_result_page() {
		//	include_once( 'views/lxt_jast_admin_results.php' );
?>
<div class="wrap">
<?php screen_icon(); ?>
<input type="hidden" id="lxt_jast_screen_id" value="<?php $screen=get_current_screen(); echo $screen->id; ?>" />
<header>
<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
<div class="lxt_jast_select_survey"><span class="lxt_jast_select_action">
<?php _e('Please select the survey you like to view: ', $this->slug); ?>
<select id="<?php echo $this->slug ?>_survey_title" ><option value=""></option>
<?php	$loop = $this->plugin->get_pub_obj()->get_post_loop(null);
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) {
				$loop->the_post();
?>
<option value="<?php echo get_the_title() ?>"><?php echo get_the_title() ?></option>
<?php
			}
		}
		wp_reset_postdata();
?>
</select>
</span></div>
</header>
<h3 class="nav-tab-wrapper">
<a href="javascript:void(0)" id="lxt_jast_image_tab" class="nav-tab nav-tab-active"><?php _e( 'Chart', $this->slug ); ?></a>
<a href="javascript:void(0)" id="lxt_jast_text_tab" class="nav-tab"><?php _e( 'Text', $this->slug ); ?></a>
</h3>
<div id="lxt_jast_result_content"/>
</div>

<?php
		//include( ABSPATH . 'wp-admin/admin-footer.php' );
	}
}

