<?php
namespace Sofutoka\Members\Database;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Key {
	static public function get_registered_key() {
		global $wpdb;
		$query = 'SELECT * FROM sftk_mmbrs_keys WHERE name = "tourokuzumi";';
		return $wpdb->get_results($query, ARRAY_A)[0];
	}

	static public function get_user_keys($user_id) {
		global $wpdb;
		$query = $wpdb->prepare(
			'SELECT sftk_mmbrs_keys.*
FROM sftk_mmbrs_keys
LEFT JOIN sftk_mmbrs_user_to_key
ON sftk_mmbrs_keys.id = sftk_mmbrs_user_to_key.key_id
WHERE sftk_mmbrs_user_to_key.user_id = %s;',
			$user_id
		);
		return $wpdb->get_results($query, ARRAY_A);
	}

	static public function get_available_keys() {
		global $wpdb;
		$query = 'SELECT * FROM sftk_mmbrs_keys;';
		return $wpdb->get_results($query, ARRAY_A);
	}

	static public function grant_user_access_to_key($user_id, $key_id) {
		global $wpdb;
		$wpdb->insert('sftk_mmbrs_user_to_key', [
			'user_id' => $user_id,
			'key_id' => $key_id,
			'acquired_at' => time(),
		]);
	}

	static public function remove_user_access_to_key($user_id, $key_id) {
		global $wpdb;
		$wpdb->delete('sftk_mmbrs_user_to_key', [
			'user_id' => $user_id,
			'key_id' => $key_id,
		]);
	}
}
