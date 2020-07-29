<?php
/**
 * Plugin Name: Innovere Quizzes & Surveys
 * Description: Innovere Quizzes & Surveys
 * Version: 1.0
 * Author: SOD
 * Author URI: 
 * Plugin URI: 
 * Text Domain: magic-quiz
 *
 * @author SOD
 * @version 1.0.0
 * @package MQZ
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MQA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'MQA_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define( 'MQA_PLUGIN_PATH', plugin_dir_path(__FILE__));

class MQA_Plugin {

	public $version = '1.0.0';

	public $helpers = null;
	public $pluginHelper = null;
	public $alertManager = null;

	public function __construct() {
		$this->load_dependencies();
		$this->add_hooks();
	}

	private function load_dependencies() {
		// if ( is_admin() ) {			
		require_once 'includes/class-alert-manager.php';
		$this->alertManager = new MQZAlertManager();

		require_once 'admin/helpers.php';
		$this->pluginHelper = new MQZ_Helpers();

		require_once 'admin/quiz-page.php';
		require_once 'admin/mqz-settings.php';
		require_once 'admin/import.php';

		require_once 'includes/class-mqz-quiz.php';			
		require_once 'includes/rest-api.php';

		require_once 'includes/class-shortcode-quiz.php';

		require_once  'includes/mailchimp/class-exception.php';
		require_once  'includes/mailchimp/class-resource-not-found-exception.php';
		require_once  'includes/mailchimp/class-connection-exception.php';
		require_once  'includes/mailchimp/class-api-v3-client.php';
		require_once  'includes/mailchimp/class-api-v3.php';


		require_once "includes/mailerlite_rest/MailerLite_Forms_Subscribers.php";
		require_once "includes/mailerlite_rest/MailerLite_Forms_Groups.php";
		require_once "includes/mailerlite_rest/MailerLite_Forms_Fields.php";
		
	}

	public function save_general_settings($settings) {
		
		$current = MQZ_Helpers::get_options();
		
		$settings = array_merge( $current, $settings );

	
		if ( strpos( $settings['api_key'], '*' ) !== false ) {		
			$settings['api_key'] = $current['api_key'];
			try {
				
				$id = isset($settings["list"]) ? $settings["list"] : "";
				if ($id)
					MQZ_Helpers::post_fields($id);
					
			} catch (MQZ_API_Exception $e) {
				//
			}

		} else if ($settings["api_key"] != "") {
			
			try {
				if (!MQZ_Helpers::get_api($settings['api_key'])->is_connected()) {
					$settings['api_key'] = "";
				}

				// $id = isset($settings["list"]) ? $settings["list"] : "";
				// if ($id)
				// 	MQZ_Helpers::post_fields();

			} catch (MQZ_API_Exception $e) {				
				$settings['api_key'] = "";
			}
		}

		$settings['api_key'] = sanitize_text_field( $settings['api_key'] );
		

		return $settings;
	}

	public function save_mailite_settings($settings) {
		$current = MQZ_Helpers::get_options();
		
		$settings = array_merge( $current, $settings );

		if ( strpos( $settings["mailite"]['api_key'], '*' ) !== false ) {
			
			$settings["mailite"]['api_key'] = $current["mailite"]['api_key'];
			$settings["mailite"]['account_id'] = $current["mailite"]['account_id'];
			$settings["mailite"]['account_subdomain'] = $current["mailite"]['account_subdomain'];
			
			try {
				
				$id = isset($settings["mailite"]["list"]) ? $settings["mailite"]["list"] : "";
				if ($id) {

				}
			} catch (MQZ_API_Exception $e) {
				
			}

		
			return $settings;
		} else if ($settings["mailite"]['api_key'] != "") {
			
			try {
				
				$settings = MQZ_Helpers::mailite_account_info($settings);

			} catch (MQZ_API_Exception $e) {
				$settings["mailite"]['api_key'] = "";
			}
			$settings["mailite"]['api_key'] = sanitize_text_field( $settings["mailite"]['api_key']  );
		}

		return $settings;
	}

	public function save_social_settings($settings) {
		$current = MQZ_Helpers::get_options();			
		$settings = array_merge( $current, $settings );
		
		// $current["social"]['twitter'] = $settings["social"]['twitter'] = $current["social"]['twitter'];
		// $settings["social"]['facebook'] = $current["social"]['facebook'];
		// $settings["social"]['instagram'] = $current["social"]['instagram'];
		
		return $settings;
	}
	public function admin_init() {
		register_setting( 'mqz_settings', 'mqz', array( $this, 'save_general_settings' ) );
		register_setting( 'mqz_maillite_settings', 'mqz', array( $this, 'save_mailite_settings' ) );
		register_setting( 'mqz_social_settings', 'mqz', array( $this, 'save_social_settings'));
		register_setting ("mqz_import_file", "mqz", array( $this, 'save_quiz_file')); 
	}

	public function save_quiz_file($settings) {
		
		if(!empty($_FILES["mqz_import_file"]["tmp_name"])) {
			$content = file_get_contents($_FILES['mqz_import_file']['tmp_name']);
			$content = trim($content);
			
			if ($content) {
				try {
					$data =  base64_decode($content);
					$data = json_decode($data, true);

					if (is_array($data) && MQZ_QUIZ::import_quiz($data)) {

					}
				}

				catch (Exception $e) {
				
				}
				
			}
			
		}

		return $settings;
	}

	public function show_import_issue() {
		printf('<div class="notice notice-import"><p>Import Failed</p></div>');
	}

	private function add_hooks() {
		add_action( 'admin_menu', array( $this, 'setup_admin_menu' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ), 900 );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'init', array( $this, 'register_quiz_post_types' ) );
		add_action( 'init', array( $this, 'rewrite_rules' ), 9999 );
		add_action( 'init', array( $this, 'mqz_export_data'), -999);

		add_action( 'load-post-new.php', array($this, 'plugin_load_page_template' ) );
		add_action( 'load-post.php', array($this, 'plugin_load_page_template' ) );
		add_shortcode( 'innovere-survey', array('Quiz_Shortcode', 'output'));
		// add_action('wp_enqueue_scripts', array($this, 'add_enque_scripts'));

		add_action('wp_ajax_nopriv_send_mailchimp', array($this, 'send_mailchimp'));
		add_action('wp_ajax_send_mailchimp', array($this, 'send_mailchimp'));

		add_action('wp_ajax_mqz_file_upload', array($this, 'mqz_file_upload'));
		add_action('wp_ajax_nopriv_mqz_file_upload', array($this, 'mqz_file_upload'));

		add_action( 'admin_enqueue_scripts', array ($this, 'mqz_admin_enque_scripts'));
	}

	public function mqz_export_data() {
		if (isset($_POST["mqz-export-id"]) && $_POST["mqz-export-id"]) {
			
			$quiz = MQZ_QUIZ::load_quiz( $_POST["mqz-export-id"] );

			if ($quiz) {

				$id = $_POST["mqz-export-id"];

				$res = json_encode($quiz);
				$res =  base64_encode($res);

				$filename = "$id.txt";
	            header("Pragma: public");
	            header("Expires: 0");
	            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	            header("Content-Type: application/octet-stream");
	            header("Content-Disposition: attachment; filename=$filename");
	            header("Content-Transfer-Encoding: binary");
	            header("Content-Length: ". strlen($res));
				
				echo $res;
				exit;
			}
		}
	}

	public function mqz_admin_enque_scripts() {
		global $pagenow;

		if ($pagenow == "admin.php" && isset($_GET["page"]) && $_GET["page"] == "innovere-survey/innovere.php") {
			wp_enqueue_script( 'jquery-ui-tooltip' );

		}

		wp_enqueue_style( 'admin-quiz.css', MQA_PLUGIN_URL . 'admin/css/mqz-admin.css', null, '1.0');
		wp_enqueue_script( 'micromodal.min.js', MQA_PLUGIN_URL . 'admin/js/micromodal.min.js', array( 'jquery'), '1.0', true );
		wp_enqueue_script( 'admin-quiz.js', MQA_PLUGIN_URL . 'admin/js/script.js', array( 'jquery'), '1.0', true );
	}

	

	public function rewrite_rules() {
		if (is_admin()) {
			flush_rewrite_rules(true);
		}
	}

	public function register_quiz_post_types() {

		$has_archive    = true;
		$exclude_search = false;
		$cpt_slug       = 'quiz';
		$plural_name    = __( 'Quiz', 'mqz' );

        $quiz_labels = array(
			'name'               => $plural_name,
			'singular_name'      => __( 'Quiz', 'mqz' ),
			'menu_name'          => __( 'Quiz', 'mqz' ),
			'name_admin_bar'     => __( 'Quiz', 'mqz' ),
			'add_new'            => __( 'Add New', 'mqz' ),
			'add_new_item'       => __( 'Add New Quiz', 'mqz' ),
			'new_item'           => __( 'New Quiz', 'mqz' ),
			'edit_item'          => __( 'Edit Quiz', 'mqz' ),
			'view_item'          => __( 'View Quiz', 'mqz' ),
			'all_items'          => __( 'All Quizzes', 'mqz' ),
			'search_items'       => __( 'Search Quizzes', 'mqz' ),
			'parent_item_colon'  => __( 'Parent Quiz:', 'mqz' ),
			'not_found'          => __( 'No Quiz Found', 'mqz' ),
			'not_found_in_trash' => __( 'No Quiz Found In Trash', 'mqz' ),
		);

		// Prepares post type array.
		$quiz_args = array(
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'labels'              => $quiz_labels,
			'publicly_queryable'  => true,
			'exclude_from_search' => $exclude_search,
			'label'               => $plural_name,
			'rewrite'             => true,
			'has_archive'         => $has_archive,
			'supports'            => array( 'title')
		);

		// Registers post type.
		register_post_type( 'quiz', $quiz_args );
		
		if (is_admin()) {
			$post_type = isset($_GET["post_type"]) ? $_GET["post_type"] : "";
			$id = isset($_GET["id"]) ? $_GET["id"] : 0;

			if ($post_type == "quiz" && $id == "new") {
				$id = MQZ_QUIZ::save_quiz(0, array(
					"quiz" => "",
					"questions" => array(),
				));
				
				if ($id > 0) {
					$url = admin_url("post-new.php?post_type=quiz&id=$id");
					wp_safe_redirect($url);
				}
			}
		}
		
	}

	public function mqz_file_upload() {
		
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
	
		if($_FILES['mqz_file'])
		{
			$mqz_file = $_FILES["mqz_file"]["name"];
			$mqz_tmp_name = $_FILES["mqz_file"]["tmp_name"];
			$error = $_FILES["mqz_file"]["error"];

			if($error > 0){
				$response = array(
					"status" => "error",
					"error" => true,
					"message" => "Error uploading the file!"
				);
			}else 
			{
				$uploads   = wp_upload_dir();
				$file_upload = $uploads["basedir"];
				$file_url = $uploads["baseurl"];
				$upload_name = $file_upload . "/" . $mqz_file;

				if(move_uploaded_file($mqz_tmp_name , $upload_name)) {
					$response = array(
						"status" => "success",
						"error" => false,
						"message" => "File uploaded successfully",
						"url" => $file_url ."/".$mqz_file
					);
				}else
				{
					$response = array(
						"status" => "error",
						"error" => true,
						"message" => "Error uploading the file!"
					);
				}
			}
		}else{
			$response = array(
				"status" => "error",
				"error" => true,
				"message" => "No file was sent!"
			);
		}

		echo json_encode($response);
	}
	
	public function send_mailchimp() {
		$quiz = isset( $_POST["quiz"] ) ? $_POST["quiz"] : '';
		$email = isset( $_POST["EMAIL"] ) ? $_POST["EMAIL"] : '';
		$name = isset( $_POST["FNAME"] ) ? $_POST["FNAME"] : '';
	
		if ($quiz && $email && $name) {
			$api = MQZ_Helpers::get_api();
			$id = MQZ_Helpers::get_options()["list"];

			$existing_member_data = $api->get_list_member( $id, $email );
			$args =  array(
				'email_address' =>  $email,
				'interests' => array(),
				'merge_fields' => array(
					'QUIZ' => $quiz,
					'EMAIL' => $email,
					'FNAME' => $name
				),
				'status' => 'subscribed',
			);

			if ($existing_member_data) {
				$res = $api->update_list_member(
					$id, $email, 
					$args
				);
			} else {
				$api->add_new_list_member( $id, $args );
			}

			echo "success";
		} else {
			echo "fail";
		}
		
		die();
	}

	public function setup_admin_menu() {
		if ( function_exists( 'add_menu_page' ) ) {
			add_menu_page( 'Innovere Surveys', __( 'Innovere Surveys', 'mqz' ), 'moderate_comments', __FILE__, 'quiz_manage_page', 'dashicons-feedback' );
			add_submenu_page( NULL, __( 'Settings', 'mqz' ), __( 'Settings', 'mqz' ), 'moderate_comments', 'quiz_manage_page' );			
			add_submenu_page( __FILE__, __( 'Settings', 'mqz' ), __( 'Settings', 'mqz' ), 'manage_options', 'mqz_options', 'mqz_settings_page'  );

			// add_submenu_page( __FILE__, __( 'Export / Import', 'mqz' ), __( 'Export / Import', 'mqz' ), 'manage_options', 'mqz_import', 'mqz_import_page'  );

		}
	}

	public function plugin_load_page_template() {
		global $pagenow;
		if ( $pagenow == 'post-new.php' && $_GET['post_type'] == 'quiz' ){
			
			wp_enqueue_media();
			wp_enqueue_style( 'thickbox' );
			add_filter('show_admin_bar', '__return_false');
			remove_action( 'in_admin_header', 'wp_admin_bar_render', 0);

			include( plugin_dir_path( __FILE__ ) . 'builder/index.php' );
			die;
		}

		if ( $pagenow == 'post.php' &&  isset( $_GET['post'] ) && ( $_GET['action'] == 'edit' ) ){
			$post_type = get_post_type( $_GET['post'] );
			if ( 'quiz' == $post_type ){				
				
				wp_enqueue_media();
				wp_enqueue_style( 'thickbox' );
				add_filter('show_admin_bar', '__return_false');

				include( plugin_dir_path( __FILE__ ) . 'builder/index.php' );
				die;		
			}
		}
	}

	public function admin_head() {

    }
    
    public function install() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
  
		$quiz_table_name = $wpdb->prefix . "innovere_survey";		
		$results_table_name = $wpdb->prefix . "mqz_results";
		
		if( $wpdb->get_var( "SHOW TABLES LIKE '$quiz_table_name'" ) != $quiz_table_name ) {
			$sql = "CREATE TABLE $quiz_table_name (
				quiz_id mediumint(9) NOT NULL,
				quiz_name TEXT NOT NULL,
				quiz_data BLOB,
				PRIMARY KEY  (quiz_id)
			) $charset_collate;";
  
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
		
    }
}

global $maq_plugin;
$maq_plugin = new MQA_Plugin();
register_activation_hook( __FILE__, array( $maq_plugin, 'install' ) );
?>
