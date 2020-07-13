<?php
/**
 * This file handles all of the current REST API endpoints
 *
 * @since 5.2.0
 * @package QSM
 */

add_action( 'rest_api_init', 'mqz_register_rest_routes' );

/**
 * Registers REST API endpoints
 *
 * @since 5.2.0
 */
function mqz_register_rest_routes() {
	register_rest_route( 'magic-quiz/v1', '/quiz/(?P<id>\d+)',array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'mqz_get_quiz'
	));
	
	register_rest_route( 'magic-quiz/v1', '/quiz/(?P<id>\d+)', array(
		'methods'  => WP_REST_Server::EDITABLE,
		'callback' => 'mqz_save_quiz',
	) );

	register_rest_route( 'magic-quiz/v1', '/mailite', 
	array(
		'methods'  => WP_REST_Server::READABLE,
		'callback' => 'mailite_field',
	));

	register_rest_route( 'magic-quiz/v1', '/send', 
	array(
		'methods'  => WP_REST_Server::EDITABLE,
		'callback' => 'mailte_send',
	));

	register_rest_route( 'magic-quiz/v1', '/fileupload', 
	array(
		'methods'  => WP_REST_Server::EDITABLE,
		'callback' => 'mqz_fileupload',
	));
}

function mqz_fileupload(WP_REST_Request $request) {

	$files = $request->get_file_params();
	$file = "";
	if ( !empty( $files ) && !empty( $files['file'] ) ) {
		$file = $files['file'];
	}

	try {
		
		if (! $file ) {
		  throw new Exception ( 'Error' );
		}
		
		if (! is_uploaded_file( $file['tmp_name'] ) ) {
		  throw new Exception ( 'File upload check failed ');
		}
		
		if (! $file['error'] === UPLOAD_ERR_OK ) {
		  throw new Exception ( 'Upload error: ' . $file['error'] );
		}
		
	  

	  	$mqz_file = $file ["name"];	  

		$uploads   = wp_upload_dir();
		$file_upload = $uploads["basedir"];
		$file_url = $uploads["baseurl"];
		$upload_name = $file_upload . "/" . $mqz_file;

		if ( move_uploaded_file($file['tmp_name']  , $upload_name)) {
			$response = array(
				"status" => "success",
				"error" => false,
				"message" => "File uploaded successfully",
				"url" => $file_url ."/".$mqz_file
			);
		} else
		{
			$response = array(
				"status" => "error",
				"error" => true,
				"message" => "Error uploading the file!"
			);
		}

		return $response;
	} catch ( Exception  $pe ) {
		return $response = array(
			"status" => "error",
			"error" => true,
			"message" => $pe->getMessage()
		);
	}
}

function mailte_send( WP_REST_Request $request) {
	$email = isset($request['email']) ? $request['email'] : "";
	$fields =  isset($request['fields']) ? $request['fields'] : "";

	if ($email) {
		MQZ_Helpers::mailite_form_subscript($fields, $email);
	} else {
		return array(
			'status' => 'error',
			'msg'    => '',
		);
	}

	return array(
		'status' => 'success',
		'msg' => 'success'
	);
}

function mailite_field( WP_REST_Request $request) {
	return MQZ_Helpers::mailite_form_field();
}

function mqz_get_quiz( WP_REST_Request $request) {
	
	// $current_user = wp_get_current_user();
	// if ( 0 !== $current_user ) {
		$quiz = MQZ_QUIZ::load_quiz( $request['id'] );
		
		return $quiz;
	// }
	
	return array(
		'status' => 'error',
		'msg'    => 'User not logged in',
	);
}

function mqz_save_quiz( WP_REST_Request $request) {
	
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	//$current_user = wp_get_current_user();
	// if ( 0 !== $current_user ) {
		$data = $request["data"];
		$id = isset($request["id"]) ? $request["id"]: 0;

		$quiz = MQZ_QUIZ::save_quiz( $id, $data );
	
		if ($quiz) {
			return array(
				'status' => 'success',
				'id' => $quiz
			);
		} else {
			return array(
				'status' => 'error',
			);
		}
		
	//}
	

	return array(
		'status' => 'error',
		'msg'    => 'User not logged in',
	);
}

// function my_customize_rest_cors() {
// 	remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
// 	add_filter( 'rest_pre_serve_request', function( $value ) {
// 		header( 'Access-Control-Allow-Origin: *' );
// 		header( 'Access-Control-Allow-Methods: GET' );
// 		header( 'Access-Control-Allow-Credentials: true' );
// 		header( 'Access-Control-Expose-Headers: Link', false );

// 		return $value;
// 	} );
// }

// add_action( 'rest_api_init', 'my_customize_rest_cors', 15 );