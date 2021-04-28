<?php
namespace Sofutoka\Members\Admin;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Ajax {
	static public function register_endpoints() {
		add_action('wp_ajax_sftk_mmbrs_get_keys', '\Sofutoka\Members\Admin\Ajax::get_keys');
		add_action('wp_ajax_sftk_mmbrs_get_locks', '\Sofutoka\Members\Admin\Ajax::get_locks');
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_get_keys')
	 */
	static public function get_keys() {
		$keys = \Sofutoka\Members\Database\Key::get_available_keys();
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode([
			'create_key_nonce' => wp_create_nonce('sftk_mmbrs_create_key'),
			'update_key_nonce' => wp_create_nonce('sftk_mmbrs_update_key'),
			'delete_key_nonce' => wp_create_nonce('sftk_mmbrs_delete_key'),
			'keys' => $keys,
		]);
		wp_die();
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_get_locks')
	 */
	static public function get_locks() {
		$locks = \Sofutoka\Members\Database\Lock::get_available_locks();
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode([
			'create_lock_nonce' => wp_create_nonce('sftk_mmbrs_create_lock'),
			'update_lock_nonce' => wp_create_nonce('sftk_mmbrs_update_lock'),
			'delete_lock_nonce' => wp_create_nonce('sftk_mbbrs_delete_lock'),
			'locks' => $locks,
		]);
		wp_die();
	}
}
