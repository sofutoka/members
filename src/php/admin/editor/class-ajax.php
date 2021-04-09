<?php
namespace sofutoka\members\admin\editor;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

require_once dirname(__FILE__, 3) . '/database/class-util.php';

class Ajax {
	static public function register_endpoints() {
		add_action('wp_ajax_sftk_mmbrs_editor_get_available_locks', '\sofutoka\members\admin\editor\Ajax::get_available_locks');
	}

	/**
	 * @attaches-to add_action('wp_ajax_sftk_mmbrs_editor_get_available_locks')
	 */
	static public function get_available_locks() {
		$locks = \sofutoka\members\database\Util::get_available_locks();
		echo json_encode($locks);
		wp_die();
	}
}
