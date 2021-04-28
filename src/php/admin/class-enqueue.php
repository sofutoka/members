<?php
namespace Sofutoka\Members\Admin;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Enqueue {
	/**
	 * @attaches-to add_action('admin_enqueue_scripts')
	 */
	static public function enqueue_assets($hook_suffix) {
		$pages_suffix = 'members_page_sftk_mmbrs_';
		$toplevel_suffix = 'toplevel_page_sftk_mmbrs';

		// グローバル
		if (
			$hook_suffix === $toplevel_suffix ||
			substr($hook_suffix, 0, strlen($pages_suffix)) === $pages_suffix
		) {
			wp_register_style(
				'sftk-mmbrs-admin-main-css',
				SFTK_MMBRS_ROOT_URL . '/dist/css/admin-main.css'
			);
			wp_enqueue_style('sftk-mmbrs-admin-main-css');
		}

		// ロックとカギの設定
		if (
			$hook_suffix === $pages_suffix . 'keys_locks_settings' &&
			// こうすればフィルターはこのページだけに実行される
			apply_filters('sftk_mmbrs__admin_enqueue_keys_locks_settings_scripts', true, 'sftk_mmbrs__admin_enqueue_keys_locks_settings_scripts')
		) {
			wp_enqueue_script(
				'sftk-mmbrs-admin-keys-editor',
				SFTK_MMBRS_ROOT_URL . '/dist/js/admin-keys-editor.js',
				[
					'wp-blocks',
					'wp-element',
					'wp-editor',
					'wp-plugins',
					'wp-edit-post',
					'wp-data',
				],
				SFTK_MMBRS_VERSION,
				true
			);
			wp_enqueue_script(
				'sftk-mmbrs-admin-locks-editor',
				SFTK_MMBRS_ROOT_URL . '/dist/js/admin-locks-editor.js',
				[
					'wp-blocks',
					'wp-element',
					'wp-editor',
					'wp-plugins',
					'wp-edit-post',
					'wp-data',
				],
				SFTK_MMBRS_VERSION,
				true
			);
		}
	}
}
