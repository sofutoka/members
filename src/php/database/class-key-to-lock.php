<?php
namespace Sofutoka\Members\Database;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Key_To_Lock {
	static public function get_records_for_lock($lock_id) {
		global $wpdb;
		$query = $wpdb->prepare(
			'SELECT *
FROM sftk_mmbrs_key_to_lock
WHERE lock_id = %s;',
			$lock_id
		);
		return $wpdb->get_results($query, ARRAY_A);
	}
}
