<?php
namespace sofutoka\members\admin\editor;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Ajax {
	static public function register_endpoints() {
		add_action('wp_ajax_sftk_mmbrs_editor_get_available_locks', '\sofutoka\members\admin\editor\Ajax::get_available_locks');
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_editor_get_available_locks')
	 */
	static public function get_available_locks() {
		$locks = \sofutoka\members\database\Lock::get_available_locks();
		$locks = array_map(function ($row) {
			return [
				'id' => $row['id'],
				'label' => $row['label'],
			];
		}, $locks);
		echo json_encode($locks);
		wp_die();
	}
}
