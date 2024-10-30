<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   lxt_jast
 * @author    Li xintao <isurgeli@gmail.com>
 * @license   GPL-2.0+
 * @link      http://isurge.worpress.com
 * @copyright 2013 Li xintao
 */

class lxt_jast_option_page
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    protected $options;
	protected $ver;
	protected $slug;
	protected $plugin;
    /**
     * Start up
     */
    public function __construct() {
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();

        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
        add_options_page(
            __('Survey settings', $this->slug), 
            __('Survey', $this->slug), 
            'manage_options', 
            'lxt_jast-setting', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
		// Set class property
		if ( function_exists( 'is_multisite' ) && is_multisite() ) 
			$this->options = get_blog_option( get_current_blog_id(), "{$this->slug}_option" );
		else
			$this->options = get_option( "{$this->slug}_option" );

        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Survey Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'lxt_jast_option_group' );   
                do_settings_sections( 'lxt_jast-setting' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {        
        register_setting(
            'lxt_jast_option_group', // Option group
            'lxt_jast_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'lxt_jast_general', // ID
            __('General Setting', $this->slug), // Title
            array( $this, 'print_section_info' ), // Callback
            'lxt_jast-setting' // Page
        );  

        add_settings_field(
            'per_slug', // ID
            __('Permalink Slug', $this->slug), // Title 
            array( $this, 'per_slug_callback' ), // Callback
            'lxt_jast-setting', // Page
            'lxt_jast_general' // Section           
        );      

        add_settings_field(
            'item_page', 
            __('Results per page', $this->slug), 
            array( $this, 'item_page_callback' ), 
            'lxt_jast-setting', 
            'lxt_jast_general'
		);      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
        $new_input = array();
        if( isset( $input['per_slug'] ) )
            $new_input['per_slug'] = sanitize_text_field( $input['per_slug'] );

        if( isset( $input['item_page'] ) )
            $new_input['item_page'] = absint( $input['item_page'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
	public function print_section_info() {
		print '<p/>';
		//print __('Permalink Slug: Permalink Slug of SURVEY post type.', $this->slug);
		//print '</p><p>';
		//print __('Results per page: Show how many results one page in SURVEY result table.', $this->slug);
		//print '</p>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function per_slug_callback() {
        printf(
            '<input type="text" id="per_slug" name="lxt_jast_option[per_slug]" value="%s" /> ' . __('Permalink Slug of SURVEY post type.', $this->slug),
            isset( $this->options['per_slug'] ) ? esc_attr( $this->options['per_slug']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function item_page_callback() {
        printf(
            '<input type="text" id="title" name="lxt_jast_option[item_page]" value="%s" /> ' . __('Show how many results one page in SURVEY result table.', $this->slug),
            isset( $this->options['item_page'] ) ? esc_attr( $this->options['item_page']) : ''
        );
    }
}

