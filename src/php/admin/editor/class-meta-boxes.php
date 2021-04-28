<?php
namespace Sofutoka\Members\Admin\Editor;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Meta_Boxes {
	/**
	 * @attaches-to add_action('init')
	 */
	public static function register_meta_boxes() {
		// ロックを選べるためのmeta
		register_post_meta(
			'', // 空っぽであれば全部のpostのタイプに当てはまる
			'_sftk_mmbrs_lock_id', // _だからcustom fieldが表示されてもこれは表示されない
			[
				'show_in_rest' => true,
				'type' => 'string',
				'auth_callback' => function () {
					return current_user_can('add_users');
				},
			]
		);
	}
}
