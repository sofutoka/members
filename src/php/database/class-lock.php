<?php
namespace Sofutoka\Members\Database;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Lock {
	static public function get_lock($lock_id) {
		global $wpdb;
		$query = $wpdb->prepare('SELECT * FROM sftk_mmbrs_locks WHERE id = %s;', $lock_id);
		$result = $wpdb->get_results($query, ARRAY_A);
		if (count($result) > 0) {
			$result = $result[0];
			$result['behavior'] = unserialize($result['behavior']);
			return $result;
		} else {
			return null;
		}
	}

	static public function get_available_locks() {
		global $wpdb;
		$query = 'SELECT * FROM sftk_mmbrs_locks;';
		$result = $wpdb->get_results($query, ARRAY_A);
		return array_map(function ($row) {
			$row['behavior'] = unserialize($row['behavior']);
			return $row;
		}, $result);
	}

	static public function get_registered_lock() {
		global $wpdb;
		$query = 'SELECT * FROM sftk_mmbrs_locks WHERE name = "tourokuhituyou";';
		return $wpdb->get_results($query, ARRAY_A)[0];
	}
}
