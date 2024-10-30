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
 * Plugin class. This class used to do the ajax work for public plugin work.
 */

class lxt_jast_ajax {
	protected $ver;
	protected $slug;
	protected $plugin;

	public function __construct() {
		$this->plugin = lxt_jast_plugin::get_instance(); 
		$this->slug = $this->plugin->get_slug();
		$this->ver = $this->plugin->get_ver();
			
		add_action( 'wp_ajax_nopriv_'.$this->slug.'_savesurvey', array( $this, 'ajax_save_survey') );
		add_action( 'wp_ajax_'.$this->slug.'_savesurvey', array( $this, 'ajax_save_survey') );

		add_action( 'wp_ajax_nopriv_'.$this->slug.'_loadsurvey', array( $this, 'ajax_load_survey') );
		add_action( 'wp_ajax_'.$this->slug.'_loadsurvey', array( $this, 'ajax_load_survey') );

		add_action( 'wp_ajax_nopriv_'.$this->slug.'_getsurveyret', array( $this, 'ajax_get_survey_result') );
		add_action( 'wp_ajax_'.$this->slug.'_getsurveyret', array( $this, 'ajax_get_survey_result') );

		if (is_admin()){
			add_action( 'wp_ajax_'.$this->slug.'_chart_frame', array( $this, 'ajax_get_chart_frame') );		
			add_action( 'wp_ajax_'.$this->slug.'_text_frame', array( $this, 'ajax_get_text_frame') );	
			add_action( 'wp_ajax_'.$this->slug.'_text_table', array( $this, 'ajax_get_text_table') );	
		}
	}

	public function ajax_get_chart_frame() {
		//$attr = [];
		//$attr = shortcode_atts( array(
		//	'title' => $_POST["title"],
		//	'name' => null,
		//	'type' => null
		//), $attr );
	
		//echo $this->plugin->get_pub_obj()->get_survey_chart_frame($attr);
		$title = $_POST["title"];
		echo $this->plugin->get_pub_obj()->get_survey_admin_result_frame($title, 'img');
		die();
	}

	public function ajax_get_text_frame() {
		$title = $_POST["title"];

		if ( !array_key_exists ('page', $_REQUEST) )
			$_REQUEST['page'] = 0;
	
		echo $this->plugin->get_pub_obj()->get_survey_admin_result_frame($title, 'text');
		die();
	}

	public function ajax_get_text_table() {
		$screen_id = $_POST["screen_id"];
		$name = $_POST["name"];
		$post_id = $_POST["post_id"];

		require_once (plugin_dir_path(__FILE__) . '../admin/views/lxt_jast_result_table.php');

	    $testListTable = new lxt_jast_result_table( $screen_id, $post_id, $name);
		$testListTable->prepare_items();
		$testListTable->display();

		die();
	}

	public function ajax_load_survey() {
		$postid = $_POST["id"];
	
		$this->plugin->get_pub_obj()->get_survey_content( $postid );
		die();
	}

	public function ajax_save_survey() {
		global $wpdb;
		global $current_user;

		$table_name = lxt_jast_plugin::get_table_name();
		$result = str_replace('\\', '', esc_sql ($_POST["result"]));
		$post_id = esc_sql ($_POST["postid"]);
		$user = '';
		if (!is_user_logged_in()) {
			if ( array_key_exists('email', $_POST) )
				$email = esc_sql ($_POST["email"]);
			else
				$email = '';

			$user = __('Visitor', $this->slug);
		}else{
			get_currentuserinfo();
			$email = $current_user->user_email;
			$user = $current_user->user_login;
		}
		
		$wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'postid' => $post_id, 'user' =>  $user, 'result' => $result, 'email' => $email ) );
		echo __("Thank you for your participate", $this->slug);
		die();
	}

	public function ajax_get_survey_result() {
		global $wpdb;

		$post_id = esc_sql ($_POST["postid"]);
		$key = esc_sql ($_POST["key"]);
		$table_name = lxt_jast_plugin::get_table_name();

		$querystr = "SELECT $table_name.result FROM $table_name WHERE $table_name.postid = '$post_id'";
		$rets = $wpdb->get_col( $querystr );

		$data = array();

		foreach ( $rets as $ret ) {
			preg_match_all ('/"' . $key . '":"([^"]+)"/', $ret, $pat_array);

			if (count($pat_array[0]) == 0) 
				continue;

			$answer = $pat_array[1][0];
			$answers = explode(",", $answer);

			foreach ( $answers as $single_an) {
				if ( !array_key_exists( $single_an, $data) )
					$data[$single_an] = 1;
				else
					$data[$single_an] = $data[$single_an] + 1;
			}
		}
		$data[$this->slug . '_total'] = count($rets);
		echo json_encode($data);
		die();
	}
}

