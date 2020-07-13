<?php
/**
 * File that contains class for questions.
 *
 * @package MQZ
 */

/**
 * Class that handles all creating, saving, and deleting of questions.
 *
 * @since 5.2.0
 */
class MQZ_QUIZ {

	/**
	 * Loads single quiz using quiz ID
	 *
	 * @since 5.2.0
	 * @param int $quiz_id The ID of the quiz.
	 * @return array The data for the quiz.
	 */

	public static function load_quiz( $quiz_id ) {
		global $wpdb;
		$quiz_id = intval( $quiz_id );
		$quiz = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}innovere_survey WHERE quiz_id = %d LIMIT 1", $quiz_id ), 'ARRAY_A' );

		if ( ! is_null( $quiz ) ) {
			
			$quiz_data = maybe_unserialize( $quiz['quiz_data'] );
			if ( ! is_array( $quiz_data ) ) {
				$quiz_data = array();
			}

			return $quiz_data;
		}

		return false;
	}

	public static function save_quiz( $quiz_id, $data) {

		if (!$data["quiz"]) {
			return false;
		}

		global $wpdb;
		$quiz_id = intval( $quiz_id );
		//$string = maybe_serialize($data);

		$values = array(			
			'quiz_id' => $quiz_id,
			'quiz_name'			=> $data["quiz"],
			'quiz_data'			=> '',
		);

		$types = array(
			'%d',			
			'%s',
			'%s',
		);
		
		$results = false;
		$slug = $data["options"]["slug"] ? $data["options"]["slug"] : $data["quiz"];
		if ( $quiz_id == 0 ) {

			$quiz_id = wp_insert_post(array (
				'post_type' => 'quiz',				
				'post_title' => wp_strip_all_tags($data["quiz"]),
				'post_content' => "[innovere-survey]",
				'post_status' => 'publish',				
			));

			$values["quiz_id"] = $quiz_id;
			$slug = wp_unique_post_slug( sanitize_title( $slug ), $quiz_id, "publish", "quiz", 0 );

			$data["options"]["slug"] = $slug;
			$string = maybe_serialize($data);
			$values["quiz_data"] = $string;

			$results = $wpdb->insert(
				$wpdb->prefix . 'innovere_survey',
				$values,
				$types
			);

			$where = array( 'ID' => $quiz_id );
			$wpdb->update( $wpdb->posts, array( 'post_name' => $slug ), $where );

			return $quiz_id;
		} else {

			$slug = wp_unique_post_slug( sanitize_title( $slug ), $quiz_id, "publish", "quiz", 0 );

			$post = array(
				'ID' =>  $quiz_id,				
				'post_title' => wp_strip_all_tags($data["quiz"]),
				'post_name' => $slug,
			);
			wp_update_post($post, true);

			$data["options"]["slug"] = $slug;
			$string = maybe_serialize($data);
			$values["quiz_data"] = $string;

			$results = $wpdb->update(
				$wpdb->prefix . 'innovere_survey',
				$values,
				array( 'quiz_id' => $quiz_id),
				$types,
				array( '%d' )
			);

			
			return $data["options"]["slug"];
		}

		return $results;
	}

	public static function delete_quiz($quiz_id) {
		global $wpdb;
		$quiz_id = intval( $quiz_id );
		$table = $wpdb->prefix . "innovere_survey";
		wp_delete_post( $quiz_id, true );
		$wpdb->delete( $table, array( 'quiz_id' => $quiz_id ) );
	}

	public function duplicate_quiz($quiz_id) {
		global $wpdb;
		$ppp = get_post( $quiz_id );
		
		if (isset( $ppp ) && $ppp != null) {
 
		/*
		 * new post data array
		 */
		$args = array(
			'comment_status' => $ppp->comment_status,
			'ping_status'    => $ppp->ping_status,
			'post_content'   => $ppp->post_content,
			'post_excerpt'   => $ppp->post_excerpt,
			'post_name'      => $ppp->post_name,
			'post_parent'    => $ppp->post_parent,
			'post_password'  => $ppp->post_password,
			'post_status'    => 'publish',
			'post_title'     => $ppp->post_title,
			'post_type'      => $ppp->post_type,
			'to_ping'        => $ppp->to_ping,
			'menu_order'     => $ppp->menu_order
		);
 
		$new_post_id = wp_insert_post( $args );
		
		$table = $wpdb->prefix . "innovere_survey";
		$sql = "SELECT * FROM $table WHERE quiz_id = $quiz_id";

		$res = $wpdb->get_results($sql, ARRAY_A);
		
			if (count($res) > 0) {
				$mqz_id = $res[0]["quiz_id"];
				$mqz_name = $res[0]["quiz_name"];
				$data = maybe_unserialize($res[0]["quiz_data"]);
				$data["options"]["slug"] = get_post_field('post_name', $new_post_id);
				$mqz_data = esc_sql(maybe_serialize($data));

				$ins_sql = "INSERT INTO $table(`quiz_id`, `quiz_name`, `quiz_data`) VALUES('$new_post_id', '$mqz_name', '$mqz_data')";				
				
				$wpdb->query($ins_sql);
			}
		}
	}
		
}
