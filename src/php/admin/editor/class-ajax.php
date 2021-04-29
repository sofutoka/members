<?php
namespace Sofutoka\Members\Admin\Editor;

use Sofutoka\Members\Util;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Ajax {
	static public function register_endpoints() {
		add_action('wp_ajax_sftk_mmbrs_editor_get_available_locks', '\Sofutoka\Members\Admin\Editor\Ajax::get_available_locks');
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_editor_get_available_locks')
	 */
	static public function get_available_locks() {
		// Util::verify_capability('add_users');
		$locks = \Sofutoka\Members\Database\Lock::get_available_locks();
		$locks = array_map(function ($row) {
			return [
				'id' => $row['id'],
				'label' => $row['label'],
			];
		}, $locks);
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($locks);
		wp_die();
	}
}
