<?php
/**
 * Plugin Name: メンバーズ：会員のみコンテンツ
 * Plugin URI: https://sofutoka.com/members
 * Description: 登録していないユーザーにコンテンツをブロックできるプラグイン。
 * Version: __VERSION__
 * Author: ソフト家
 * Author URI: https://sofutoka.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Sofutoka\Members;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

spl_autoload_register(function ($classname) {
	if (substr($classname, 0, strlen('Sofutoka\Members\\')) === 'Sofutoka\Members\\') {
		$classname = substr($classname, strlen('Sofutoka\Members\\'));
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

add_action('admin_menu', '\Sofutoka\Members\Admin\Navigation::register_menu_items');
add_action('adminmenu', '\Sofutoka\Members\Admin\Navigation::translate_menu_items');
add_action('admin_enqueue_scripts', '\Sofutoka\Members\Admin\Enqueue::enqueue_assets');
\Sofutoka\Members\Admin\Ajax::register_endpoints();

/**
 * データーベース
 */

register_activation_hook('sofutoka-members/sofutoka-members.php', '\Sofutoka\Members\database\Setup::handle_activation');
register_uninstall_hook('sofutoka-members/sofutoka-members.php', '\Sofutoka\Members\database\Setup::handle_uninstall');

/**
 * アクセス
 */

add_action('wp', '\Sofutoka\Members\Gatekeeper::gatekeep_access');
add_action('user_register', '\Sofutoka\Members\Register::grant_registered_key');

/**
 * エディター
 */

\Sofutoka\Members\Admin\Editor\Ajax::register_endpoints();
add_action('admin_enqueue_scripts', '\Sofutoka\Members\Admin\Editor\Enqueue::enqueue_assets');
add_action('init', '\Sofutoka\Members\Admin\Editor\Meta_Boxes::register_meta_boxes');

/**
 * プロフィール
 */

\Sofutoka\Members\Admin\Profile\Ajax::register_endpoints();
add_action('admin_enqueue_scripts', '\Sofutoka\Members\Admin\Profile\Enqueue::enqueue_assets');
add_action('edit_user_profile', '\Sofutoka\Members\Admin\Profile\Edit_Profile_Section::render_key_editor');

/**
 * ログイン
 */

add_filter('login_message', '\Sofutoka\Members\Login_page::display_redirected_notice');
