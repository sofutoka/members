<?php
namespace sofutoka\members;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

require_once dirname(__FILE__) . '/database/class-user.php';
require_once dirname(__FILE__) . '/database/class-lock.php';
require_once dirname(__FILE__) . '/class-login-page.php';

class Gatekeeper {
	/**
	 * @attaches-to add_action('wp')
	 */
	static public function gatekeep_access() {
		if (is_single() || is_page()) {
			self::gatekeep_post_access();
		} else {
			// 有料版にアップグレードの必要があります
			// 有料版はカテゴリー、タグ、などの制限ができます
			// 無料版は固定ページと投稿しか制限できません
			do_action('sftk_mmbrs__gatekeeper_gatekeep_access_else');
		}
	}

	static private function get_current_user() {
		return (is_user_logged_in() && is_object($user = wp_get_current_user()) && !empty($user->ID)) ? $user : null;
	}

	static private function handle_blocked_user($lock_id) {
		$lock = \sofutoka\members\database\Lock::get_lock($lock_id);

		switch ($lock['behavior']['type']) {
			case 'redirect':
				if ($lock['behavior']['redirect_to'] === 'wp-login.php') {
					Login_Page::set_redirected_cookie();
					auth_redirect();
				} else {
					// 有料版にアップグレードの必要があります
					do_action('sftk_mmbrs__gatekeeper_handle_blocked_user_redirect_else', $lock);
				}
				break;
			default:
				// 有料版にアップグレードの必要があります
				do_action('sftk_mmbrs__gatekeeper_handle_blocked_user_else', $lock);
		}
	}

	static private function gatekeep_post_access() {
		$is_locked = metadata_exists('post', get_the_ID(), '_sftk_mmbrs_lock_id');

		if ($is_locked) {
			$lock_id = get_metadata('post', get_the_ID(), '_sftk_mmbrs_lock_id', true);

			if (
				($user = self::get_current_user()) === null ||
				!\sofutoka\members\database\User::user_has_key_for_lock($user->ID, $lock_id)
			) {
				self::handle_blocked_user($lock_id);
			}
		}
	}
}
