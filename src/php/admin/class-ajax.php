<?php
namespace sofutoka\members\admin;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

require_once dirname(__FILE__, 2) . '/database/class-key.php';

class Ajax {
	static public function register_endpoints() {
		add_action('wp_ajax_sftk_mmbrs_get_keys', '\sofutoka\members\admin\Ajax::get_keys');
		add_action('wp_ajax_sftk_mmbrs_get_locks', '\sofutoka\members\admin\Ajax::get_locks');
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_get_keys')
	 */
	static public function get_keys() {
		$keys = \sofutoka\members\database\Key::get_available_keys();
		echo json_encode($keys);
		wp_die();
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_get_locks')
	 */
	static public function get_locks() {
		$keys = \sofutoka\members\database\Lock::get_available_locks();
		echo json_encode($keys);
		wp_die();
	}
}
