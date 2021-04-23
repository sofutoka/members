<?php
/**
 * Plugin Name: Sofutoka Members
 * Plugin URI: https://sofutoka.com/members
 * Description: 登録していないユーザーにコンテンツをブロックできるプラグイン。
 * Version: __VERSION__
 * Author: ソフト家
 * Author URI: https://sofutoka.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

spl_autoload_register(function ($classname) {
	if (substr($classname, 0, strlen('sofutoka\members\\')) === 'sofutoka\members\\') {
		$classname = substr($classname, strlen('sofutoka\members\\'));
		$classname = str_replace('_', '-', $classname);
		$parts = explode('\\', strtolower($classname));
		$class = 'class-' . array_pop($parts);
		$folders = implode('/', $parts);

		include_once __DIR__ . '/src/php/' . ($folders === '' ? '' : $folders . '/') . $class . '.php';
	}
});

define('SFTK_MMBRS_ROOT_URL', plugins_url('', __FILE__));
define('SFTK_MMBRS_VERSION', '__VERSION__');

/**
 * メニュー
 */

add_action('admin_menu', '\sofutoka\members\admin\Navigation::register_menu_items');
add_action('adminmenu', '\sofutoka\members\admin\Navigation::translate_menu_items');
add_action('admin_enqueue_scripts', '\sofutoka\members\admin\Enqueue::enqueue_assets');
\sofutoka\members\admin\Ajax::register_endpoints();

/**
 * データーベース
 */

register_activation_hook('sofutoka-members/sofutoka-members.php', '\sofutoka\members\database\Setup::handle_activation');
register_uninstall_hook('sofutoka-members/sofutoka-members.php', '\sofutoka\members\database\Setup::handle_uninstall');

/**
 * アクセス
 */

add_action('wp', '\sofutoka\members\Gatekeeper::gatekeep_access');
add_action('user_register', '\sofutoka\members\Register::grant_registered_key');

/**
 * エディター
 */

\sofutoka\members\admin\editor\Ajax::register_endpoints();
add_action('admin_enqueue_scripts', '\sofutoka\members\admin\editor\Enqueue::enqueue_assets');
add_action('init', '\sofutoka\members\admin\editor\Meta_Boxes::register_meta_boxes');

/**
 * プロフィール
 */

\sofutoka\members\admin\profile\Ajax::register_endpoints();
add_action('admin_enqueue_scripts', '\sofutoka\members\admin\profile\Enqueue::enqueue_assets');
add_action('edit_user_profile', '\sofutoka\members\admin\profile\Edit_Profile_Section::render_key_editor');

/**
 * ログイン
 */

add_filter('login_message', '\sofutoka\members\Login_page::display_redirected_notice');
