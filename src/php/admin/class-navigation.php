<?php
namespace Sofutoka\Members\Admin;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Navigation {
	/**
	 * @attaches-to add_action('admin_menu')
	 */
	static public function register_menu_items() {
		do_action('sftk_mmbrs__navigation_before');

		// sftk_mmbrs
		do_action('sftk_mmbrs__navigation_before_welcome');
		add_menu_page(
			'ソフト家のメンバーズ',
			'Members',
			'add_users',
			'sftk_mmbrs',
			'\Sofutoka\Members\Admin\Navigation::render_page'
		);
		do_action('sftk_mmbrs__navigation_after_welcome');

    // sftk_mmbrs_keys_locks_settings
		do_action('sftk_mmbrs__navigation_before_keys_locks');
		add_submenu_page(
			'sftk_mmbrs',
			'メンバーズのロックとカギの設定',
			'ロックとカギの設定',
			'add_users',
			'sftk_mmbrs_keys_locks_settings',
			'\Sofutoka\Members\Admin\Navigation::render_page'
		);
		do_action('sftk_mmbrs__navigation_after_keys_locks');

		do_action('sftk_mmbrs__navigation_after');
	}

	static public function render_page() {
		$page_id = sanitize_key($_GET['page']);
		require_once dirname(__FILE__) . '/pages/' . $page_id . '.php';
	}

	/**
	 * メニューが表示された即時にJavaScriptを使ってテキストを翻訳する
	 *
	 * @attaches-to add_action('adminmenu')
	 */
	static public function translate_menu_items() {
		echo '<script>';
		echo 'jQuery(\'#toplevel_page_sftk_mmbrs a[href="admin.php?page=sftk_mmbrs"] .wp-menu-name\').text(\'メンバーズ\');';
		echo 'jQuery(\'#toplevel_page_sftk_mmbrs a[href="admin.php?page=sftk_mmbrs"].wp-first-item\').text(\'メンバーズの説明\');';
		echo '</script>';
	}
}
