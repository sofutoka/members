<?php
namespace Sofutoka\Members\Database;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class User_To_Key {
	static public function get_records_for_user($user_id) {
		global $wpdb;
		$query = $wpdb->prepare(
			'SELECT *
FROM sftk_mmbrs_user_to_key
WHERE user_id = %s;',
			$user_id
		);
		return $wpdb->get_results($query, ARRAY_A);
	}

	static public function insert_record($user_id, $key_id) {
		global $wpdb;
		$wpdb->insert('sftk_mmbrs_user_to_key', [
			'user_id' => $user_id,
			'key_id' => $key_id,
			'acquired_at' => time(),
		]);
	}
}
