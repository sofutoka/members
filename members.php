<?php
/**
 * Plugin Name: メンバーズ
 * Plugin URI: https://sofutoka.com/members
 * Description: 登録していないお客様にコンテンツを隠す設定ができるプラグイン
 * Version: 210405
 * Author: ソフト家
 * Author URI: https://sofutoka.com
 * License: GPL2
 */

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

define('SFTK_MMBRS_ROOT_URL', plugins_url('/', __FILE__));
define('SFTK_MMBRS_VERSION', '210405');

/**
 * メニュー
 */

require_once dirname(__FILE__) . '/src/php/admin/class-navigation.php';
add_action('admin_menu', '\sofutoka\members\admin\Navigation::register_menu_items');

/**
 * ツアー
 */

if (isset($_GET['sftk_mmbrs_tour']) && $_GET['sftk_mmbrs_tour'] === '1') {
	update_option('sftk_mmbrs_tour_mode_on', true);
}

if (get_option('sftk_mmbrs_tour_mode_on') === true) {
	require_once dirname(__FILE__) . '/src/php/admin/tour/init.php';
}

/**
 * データーベース
 */

require_once dirname(__FILE__) . '/src/php/database/class-setup.php';
register_activation_hook('members/members.php', '\sofutoka\members\database\Setup::handle_activation');
register_uninstall_hook('members/members.php', '\sofutoka\members\database\Setup::handle_uninstall');

/**
 * アクセス
 */

require_once dirname(__FILE__) . '/src/php/class-gatekeeper.php';
add_action('wp', '\sofutoka\members\Gatekeeper::gatekeep_access');

/**
 * エディター
 */

require_once dirname(__FILE__) . '/src/php/admin/editor/class-ajax.php';
\sofutoka\members\admin\editor\Ajax::register_endpoints();

require_once dirname(__FILE__) . '/src/php/admin/editor/class-enqueue.php';
add_action('admin_enqueue_scripts', '\sofutoka\members\admin\editor\Enqueue::enqueue_assets');

require_once dirname(__FILE__) . '/src/php/admin/editor/class-meta-boxes.php';
add_action('init', '\sofutoka\members\admin\editor\Meta_Boxes::register_meta_boxes');
