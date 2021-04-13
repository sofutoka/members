<?php
namespace sofutoka\members\admin\profile;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

require_once dirname(__FILE__, 3) . '/database/class-key.php';

class Ajax {
	static public function register_endpoints() {
		add_action('wp_ajax_sftk_mmbrs_edit_profile_get_user_keys', '\sofutoka\members\admin\profile\Ajax::get_user_keys');
		add_action('wp_ajax_sftk_mmbrs_edit_profile_set_key_access', '\sofutoka\members\admin\profile\Ajax::set_key_access');
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_edit_profile_get_user_keys')
	 */
	static public function get_user_keys() {
		$all_keys = \sofutoka\members\database\Key::get_available_keys();
		$user_keys = \sofutoka\members\database\Key::get_user_keys($_POST['user_id']);

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
			'set_key_access_nonce' => wp_create_nonce('set_key_access_user_' . $_POST['user_id']),
		];

		echo json_encode($result);
		wp_die();
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_edit_profile_set_key_access')
	 */
	static public function set_key_access() {
		// get_user_keys()からこのnonceが来てます
		wp_verify_nonce($_POST['nonce'], 'set_key_access_user_' . $_POST['user_id']);

		if ($_POST['has_key'] === 'true') {
			\sofutoka\members\database\Key::grant_user_access_to_key($_POST['user_id'], $_POST['key_id']);
		} else {
			\sofutoka\members\database\Key::remove_user_access_to_key($_POST['user_id'], $_POST['key_id']);
		}

		echo json_encode([
			'status' => 'ok',
			'has_key' => $_POST['has_key'] === 'true',
		]);
		wp_die();
	}
}
