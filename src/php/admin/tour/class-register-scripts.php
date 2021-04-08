<?php
namespace sofutoka\members\admin\tour;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Register_Scripts {
	/**
	 * @attaches-to add_action('admin_enqueue_scripts')
	 */
	static public function register($hook_suffix) {
		/*
		$pages_suffix = 's2member-next_page_s2x_payments_log';
		$toplevel_suffix = 'toplevel_page_s2x';

		if (
			substr($hook_suffix, 0, strlen($pages_suffix)) === $pages_suffix ||
			$hook_suffix === $toplevel_suffix
		) {
			wp_register_style(
				's2member_admin_page_template',
				plugins_url('css/s2x-admin-page-template.css', dirname(__FILE__, 2))
			);
			wp_enqueue_style('s2member_admin_page_template');
		}

		wp_register_style(
			's2member_admin_global_styles',
			plugins_url('css/s2x-admin-global-styles.css', dirname(__FILE__, 2))
		);
		wp_enqueue_style('s2member_admin_global_styles');
		*/
	}
}
