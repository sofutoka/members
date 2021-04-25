<?php
namespace sofutoka\members\admin\profile;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

use \sofutoka\members\Util;

class Ajax {
	static public function register_endpoints() {
		add_action('wp_ajax_sftk_mmbrs_edit_profile_get_user_keys', '\sofutoka\members\admin\profile\Ajax::get_user_keys');
		add_action('wp_ajax_sftk_mmbrs_edit_profile_set_key_access', '\sofutoka\members\admin\profile\Ajax::set_key_access');
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_edit_profile_get_user_keys')
	 */
	static public function get_user_keys() {
		$user_id = Util::sanitize_id($_POST['user_id']);
		$all_keys = \sofutoka\members\database\Key::get_available_keys();
		$user_keys = \sofutoka\members\database\Key::get_user_keys($user_id);

		$result_keys = array_map(function ($key) use ($user_keys) {
			$key['checked'] = false;
			foreach ($user_keys as $user_key) {
				if ($user_key['id'] === $key['id']) {
					$key['checked'] = true;
					break;
				}
			}
			return $key;
		}, $all_keys);

		$result = [
			'data' => $result_keys,
			'set_key_access_nonce' => wp_create_nonce('sftk_mmbrs_set_key_access_user_' . $user_id),
		];

		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($result);
		wp_die();
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_edit_profile_set_key_access')
	 */
	static public function set_key_access() {
		$user_id = Util::sanitize_id($_POST['user_id']);
		$key_id = Util::sanitize_id($_POST['key_id']);

		// get_user_keys()からこのnonceが来てます
		Util::verify_nonce($_POST['nonce'], 'sftk_mmbrs_set_key_access_user_' . $user_id);

		if (Util::sanitize_bool($_POST['has_key'])) {
			\sofutoka\members\database\Key::grant_user_access_to_key($user_id, $key_id);
		} else {
			\sofutoka\members\database\Key::remove_user_access_to_key($user_id, $key_id);
		}

		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode([
			'status' => 'ok',
			'has_key' => Util::sanitize_bool($_POST['has_key']),
		]);
		wp_die();
	}
}
