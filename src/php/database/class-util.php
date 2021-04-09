<?php
namespace sofutoka\members\database;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Util {
	static public function get_available_locks() {
		global $wpdb;
		$query = 'SELECT id, label FROM sftk_mmbrs_locks;';
		return $wpdb->get_results($query, ARRAY_A);
	}
}
