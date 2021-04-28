<?php
namespace Sofutoka\Members\Admin\Editor;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Enqueue {
	/**
	 * @attaches-to add_action('admin_enqueue_scripts')
	 */
	static public function enqueue_assets() {
		wp_enqueue_script(
			'sftk-mmbrs-sidebar-lock',
			SFTK_MMBRS_ROOT_URL . '/dist/js/gutenberg-sidebar-lock.js',
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
