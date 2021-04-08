<?php
namespace sofutoka\members;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Gatekeeper {
	/**
	 * @attaches-to add_action('wp')
	 */
	static public function gatekeep_access() {
		if (is_single()) {
			self::gatekeep_post_access();
		} elseif (is_page()) {
			self::gatekeep_page_access();
		} else {
			// 有料版はカテゴリー、タグ、などの制限ができます
			// 無料版は固定ページと投稿しか制限できません
			do_action('sftk_mmbrs__gatekeeper_else');
		}
	}

	static private function gatekeep_post_access() {
		// TODO catgs.inc.php for reference
	}

	static private function gatekeep_page_access() {
		// TODO catgs.inc.php for reference
	}
}
