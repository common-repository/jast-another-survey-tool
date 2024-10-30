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
/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class lxt_jast_result_table extends WP_List_Table {
	protected $post_id;
	protected $qust_name;

	protected $ver;
	protected $slug;
	protected $plugin;
    
    function __construct($screen_id, $id, $name){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
			'ajax'      => false,	    //does this table support ajax?
			'screen'	=> $screen_id
        ) );

		$this->post_id = $id;
		$this->qust_name = $name;

		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();
	}
    
    function column_default($item, $column_name){
        switch($column_name){
			case 'user':
			case 'email':
			case 'content':
				return $item[$column_name];
			case 'date':
                return $item['time'];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
        
    function get_columns(){
        $columns = array(
            'user'      => __('User', $this->slug), 
            'email'     => __('Email', $this->slug), 
			'content'   => __('Content', $this->slug),
			'date'		=> __('Date', $this->slug)
        );
        return $columns;
    }
            
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
		$per_page = get_post_meta( $this->post_id, $this->slug . '_md_perpage', true );

		if ( !isset($per_page) || !is_numeric($per_page)) {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) 
				$options = get_blog_option( get_current_blog_id(), "{$this->slug}_option" );
			else
				$options = get_option( "{$this->slug}_option" );

			if( array_key_exists ( 'item_page', $options) )
				$per_page = $options['item_page'];
		}

		if ( !isset($per_page) || !is_numeric($per_page) || $per_page == 0) 
			$per_page = 10;
                
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
                
		$this->_column_headers = array($columns, $hidden, $sortable);

		$current_page = $this->get_pagenum();
        
		$total_items = $this->get_result_count();

		if ($current_page > ceil($total_items/$per_page))
			$current_page = ceil($total_items/$per_page);
        
        $data = $this->get_result_data($current_page, $per_page);
                
        $this->items = $data;
        
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
	}

	function get_result_count() {
		global $wpdb; //This is used only if making any database queries
		$table_name = lxt_jast_plugin::get_table_name();

		$querystr = "SELECT count(*) FROM $table_name WHERE $table_name.postid = '$this->post_id'";
		$rets = $wpdb->get_col( $querystr );

		return $rets[0];
	}

	function get_result_data($pagenum, $perpage) {
		global $wpdb; //This is used only if making any database queries
		$table_name = lxt_jast_plugin::get_table_name();

		$querystr = "SELECT user, email, result, time FROM $table_name WHERE $table_name.postid = '$this->post_id'";
		$querystr .= " order by time asc limit " . ($pagenum-1)*$perpage . " ," . $perpage;

		$data = $wpdb->get_results( $querystr, ARRAY_A);

		for ($i = 0; $i < count($data); $i++) {
			preg_match_all ('/"' . $this->qust_name . '":"([^"]+)"/', $data[$i]['result'], $pat_array);

			if (count($pat_array[0]) == 0) {
				$data[$i]['content'] = '';
			}else{
				$data[$i]['content'] = $pat_array[1][0];
			}
		}

		return $data;	
	}
}
