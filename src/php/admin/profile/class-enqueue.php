<?php
namespace Sofutoka\Members\Admin\Profile;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Enqueue {
	/**
	 * @attaches-to add_action('admin_enqueue_scripts')
	 */
	static public function enqueue_assets($hook_suffix) {
		if (
			current_user_can('add_users') &&
			($hook_suffix === 'user-edit.php' || $hook_suffix === 'profile.php')
		) {
			wp_enqueue_script(
				'sftk-mmbrs-edit-profile-keys-editor',
				SFTK_MMBRS_ROOT_URL . '/dist/js/edit-profile-keys-editor.js',
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

			wp_register_style(
				'sftk-mmbrs-admin-main-css',
				SFTK_MMBRS_ROOT_URL . '/dist/css/admin-main.css'
			);
			wp_enqueue_style('sftk-mmbrs-admin-main-css');
		}
	}
}
