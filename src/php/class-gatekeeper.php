<?php
namespace Sofutoka\Members;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

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
		return (
			(
				is_user_logged_in() &&
				is_object($user = wp_get_current_user()) &&
				!empty($user->ID)
			) ? $user : null
		);
	}

	static private function handle_blocked_user($lock_id) {
		$lock = \Sofutoka\Members\Database\Lock::get_lock($lock_id);

		// TODO Handle case where we got here but no lock is found

		switch ($lock['behavior']['type']) {
			case 'redirect':
				if ($lock['behavior']['redirect_to'] === 'wp-login.php') {
					// カギないのにログインにリダイレクトしたら無限ループに入っちゃうか再ログイン
					// を強制する選択肢だから、コンテンツがそもそも存在しない様に扱う。
					self::redirect_to_404();
				} else {
					// 有料版にアップグレードの必要があります
					do_action('sftk_mmbrs__gatekeeper_handle_blocked_user_redirect_else', $lock);
				}
				break;
			case 'redirect_post':
				if ($lock['behavior']['post_id'] === 'wp-login.php') {
					// 以上と同じく。
					self::redirect_to_404();
				} else {
					// 有料版にアップグレードの必要があります
					do_action('sftk_mmbrs__gatekeeper_handle_blocked_user_redirect_post_else', $lock);
				}
			default:
				// 有料版にアップグレードの必要があります
				do_action('sftk_mmbrs__gatekeeper_handle_blocked_user_else', $lock);
		}
	}

	static private function gatekeep_post_access() {
		$post_id = get_the_ID();
		$post_lock_id = get_metadata('post', get_the_ID(), '_sftk_mmbrs_lock_id', true);
		$has_lock = $post_lock_id !== '' && $post_lock_id !== '__DISABLED__';
		$is_locked = apply_filters('sftk_mmbrs__post_is_locked', $has_lock, $post_id);

		if ($is_locked) {
			$locks = [];
			if ($has_lock) {
				$locks[] = get_metadata('post', get_the_ID(), '_sftk_mmbrs_lock_id', true);
			}
			$locks = array_merge($locks, apply_filters('sftk_mmbrs__post_is_locked_locks', [], $post_id));

			$user = self::get_current_user();

			// そもそもログインしていない場合
			if ($user === null) {
				self::redirect_to_login();
			} else {
				// このページのアクセス許可がなければ
				if (!\Sofutoka\Members\Database\User::user_has_key_for_lock($user->ID, $locks)) {
					// とるべき行動を見つける
					$lock_to_apply_id = apply_filters('sftk_mmbrs__post_is_locked_lock_to_apply', $locks[0], $locks);
					self::handle_blocked_user($lock_to_apply_id);
				}
			}
		}
	}

	static private function redirect_to_404() {
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
		get_template_part(404);
		// あえてwp_die()を使っていない
		exit();
	}

	static private function redirect_to_login() {
		Login_Page::set_redirected_cookie();
		auth_redirect();
	}
}
