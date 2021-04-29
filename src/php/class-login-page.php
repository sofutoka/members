<?php
namespace Sofutoka\Members;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Login_Page {
	static public function set_redirected_cookie() {
		$url = site_url('wp-login.php');
		$url = parse_url($url);
		setcookie(
			'sftk_mmbrs_redirected_to_login',
			'1',
			time() + 5,
			$url['path'],
			$url['host'],
			false,
			true
		);
	}

	/**
	 * @attaches-to add_filter('login_message')
	 */
	static public function display_redirected_notice($message) {
		if (isset($_COOKIE['sftk_mmbrs_redirected_to_login'])) {
			$message .= '<p>ログインが必要なページです。</p>';
			return $message;
		} else {
			return $message;
		}
	}
}
