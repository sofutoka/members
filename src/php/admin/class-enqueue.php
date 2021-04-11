<?php
namespace sofutoka\members\admin;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Enqueue {
	/**
	 * @attaches-to add_action('admin_enqueue_scripts')
	 */
	static public function enqueue_assets($hook_suffix) {
		if ($hook_suffix === 'toplevel_page_sftk_mmbrs') {
			wp_enqueue_script(
				'sftk-mmbrs-admin-keys-editor',
				SFTK_MMBRS_ROOT_URL . '/assets/js/admin-keys-editor.js',
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
				SFTK_MMBRS_ROOT_URL . '/assets/css/admin-main.css'
			);
			wp_enqueue_style('sftk-mmbrs-admin-main-css');
		}
	}
}
