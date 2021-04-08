<?php
namespace sofutoka\members\admin;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Navigation {
	/**
	 * @attaches-to add_action('admin_menu')
	 */
	static public function register_menu_items() {
		add_menu_page(
			'メンバーズ、最初に',
			'メンバーズ',
			'add_users',
			'sftk_mmbrs',
			'\sofutoka\members\admin\Navigation::render_page',
			null
		);
	}

	static public function render_page() {
		$page_id = $_GET['page'];
		require_once dirname(__FILE__) . '/pages/' . $page_id . '.php';
	}
}
