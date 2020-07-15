<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This class is a helper class to be used for extending the plugin
*
* This class contains many functions for extending the plugin
*
* @since 4.0.0
*/
class MQZ_Helpers {

	public static $version = "1.38";
	
	public function __construct() {
		
	}


	public function prepare_quiz( $quiz_id ) {
		$quiz_id = intval( $quiz_id );

		// Tries to load quiz name to ensure this is a valid ID.
		global $wpdb;
		$quiz_name = $wpdb->get_var( $wpdb->prepare( "SELECT quiz_name FROM {$wpdb->prefix}innovere_survey WHERE quiz_id=%d LIMIT 1", $quiz_id ) );
		if ( is_null( $quiz_name ) ) {
			return false;
		}
	
		return True;
	}

	public static function get_quizzes( $include_deleted = false, $order_by = 'quiz_id', $order = 'DESC' ) {
		global $wpdb;

		// Set order direction
		$order_direction = 'DESC';
		if ( 'ASC' == $order ) {
			$order_direction = 'ASC';
		}

		// Set field to sort by
		switch ( $order_by ) {

			case 'title':
				$order_field = 'quiz_name';
				break;
			
			default:
				$order_field = 'quiz_id';
				break;
		}

		// Get quizzes and return them
		$quizzes = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}innovere_survey ORDER BY $order_field $order_direction" );
		return $quizzes;
	}

	public static function get_options() {		
		$options  = (array) get_option( 'mqz', array() );
		
		return $options;
	}

	public static function get_mailite_options() {
		$options = (array) get_option('mqz', array());

		return isset($options["mailite"]) ? $options["mailite"] : false;
	}

	public static function get_mailite_list() {
		return array();
	}

	public static function get_api($api_key = null) {
		if (self::$api == null) {
			$api_key = ($api_key) ? $api_key : self::get_options()["api_key"];
			self::$api = new MQZ_API_V3( $api_key );
		}
		
        return self::$api;
	}

	public static function get_lists( $skip_cache = false ) {
		$cache_key = 'mqz_mailchimp_lists';
		$cached    = get_transient( $cache_key );

		if ( is_array( $cached ) && ! $skip_cache ) {
			return $cached;
		}

		$lists = self::fetch_lists();

		// make sure cache ttl is not lower than 60 seconds
		$cache_ttl = max( 60, $cache_ttl );
		set_transient( $cache_key, $lists, $cache_ttl );
		return $lists;
	}

	public static $api = null;
	
	public static function post_fields($id) {
		$api = self::get_api();
		try {
			$fields = $api->get_list_merge_fields($id, array(
				"fields" => array(
					"name" => "quiz",
					"type" => "text",
				)
			));
			$is_exist = false;
			foreach ($fields as $field) {
				if ($field->name == "quiz") {
					$is_exist = true;
					break;
				}
			}

			if (!$is_exist) {
				$fields = $api->put_list_merge_fields($id, 
					array(
						"tag" => "quiz",
						"name" => "quiz",
						"type" => "text",
						"public" => true,
						"default_value" => "",
					)
				);
			}
			
		} catch ( MQZ_API_Exception $e ) {			
			return array();
		}

		return $fields;
	}

	public static function mailite_form_group() {
		$api_key = self::get_mailite_options()["api_key"];
		$ML_Groups = new MailerLite_Forms_Groups($api_key );
		$data = $ML_Groups->getAllJson();
		$groups = array();
		foreach ($data as $d) {
			$groups[] = array(
				"id" => $d->id,
				"name" => $d->name
			);                            
		}

		return $groups;
	}

	public static function mailite_form_field() {
		$api_key = self::get_mailite_options()["api_key"];
		$ML_Fields = new MailerLite_Forms_Fields( $api_key );
		$fields    = $ML_Fields->getAllJson();
		return $fields;
	}

	public static function mailite_form_subscript($fields, $email) {
		$api_key = self::get_mailite_options()["api_key"];
		$ML_Subscribers = new MailerLite_Forms_Subscribers( $api_key );

		$subscriber = [
			'email'  => $email,
			'fields' => $fields,
			'date_subscribe' => date('Y-m-d'),
			'type' => 'active'
		];

		$group_id = self::get_mailite_options()["list"];;
		$ML_Subscribers->setGroupId( $group_id )->add( $subscriber, 1 );
	}	

	public static function mailite_account_info($settings = null) {
		// request to mailerlite api

		if ($settings == null) {
			$settings = self::get_options();
		}

		$ch = curl_init();
		$api_key = $settings["mailite"]["api_key"];
		
		curl_setopt_array( $ch, [
			CURLOPT_URL            => 'https://api.mailerlite.com/api/v2',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTPHEADER     => [
				'X-MailerLite-ApiKey: ' . $api_key,
			],
		] );

		$output = curl_exec( $ch );
		curl_close( $ch );

		$response = json_decode( $output );

		if ( ! empty( $response->account ) ) {
			$settings["mailite"]["account_id"] = $response->account->id;
			$settings["mailite"]["account_subdomain"] = $response->account->subdomain;

			// update_option( 'account_id', $response->account->id );
			// update_option( 'account_subdomain', $response->account->subdomain );
			// update_option( 'mailerlite_popups_disabled', false );


		} else {
			throw new MQZ_API_Exception("Mailite Exception", "9001");
		}

		return $settings;
	}

	private static function fetch_lists() {
		/**
		 * Filters the amount of Mailchimp lists to fetch.
		 *
		 * If you increase this, it might be necessary to increase your PHP configuration to allow for a higher max_execution_time.
		 *
		 * @param int
		 */
		$limit = 200;
		$api = self::get_api();
		try {
			$lists_data = $api->get_lists(
				array(
					'count'  => $limit,
					'fields' => 'lists.id,lists.name,lists.stats,lists.web_id',
				)
			);
		} catch ( MQZ_API_Exception $e ) {			
			return array();
		}

		// key by list ID
		$lists = array();
		foreach ( $lists_data as $list_data ) {
			$lists[ "$list_data->id" ] = $list_data;
		}

		return $lists;
	}

}
?>
