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
		// sftk_mmbrs
		add_menu_page(
			'ソフト家のメンバーズ',
			'Members',
			'add_users',
			'sftk_mmbrs',
			'\sofutoka\members\admin\Navigation::render_page'
		);
		// sftk_mmbrs_rokku_kagi_settei
		add_submenu_page(
			'sftk_mmbrs',
			'メンバーズのロックとカギの設定',
			'ロックとカギの設定',
			'add_users',
			'sftk_mmbrs_rokku_kagi_settei',
			'\sofutoka\members\admin\Navigation::render_page'
		);
	}

	static public function render_page() {
		$page_id = $_GET['page'];
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
		echo 'jQuery(\'#toplevel_page_sftk_mmbrs a[href="admin.php?page=sftk_mmbrs"].wp-first-item\').text(\'メンバーズ\');';
		echo '</script>';
	}
}
