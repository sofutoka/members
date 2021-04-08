<?php
namespace sofutoka\members\database;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Setup {
	static private $prefix = 'sftk_mmbrs_';

	/**
	 * @attaches-to register_activation_hook('members/members.php')
	 */
	static public function handle_activation() {
		// 実行が済んだら次回実行するのは避けたい
		if (get_option(self::$prefix . 'created_database') !== true) {
			self::create_tables();
			self::insert_default_rows();
			update_option(self::$prefix . 'created_database', true);
		}
	}

	/**
	 * @attaches-to register_uninstall_hook('members/members.php')
	 */
	static public function handle_uninstall() {
		// Drop tables
		// https://github.com/wpsharks/s2x-payments-log/blob/master/includes/drop-tables.php
		// https://github.com/wpsharks/s2x-payments-log/blob/master/includes/hooks/register_uninstall_hook.php
	}

	static private function create_table($table_name, $table_definition) {
		// https://codex.wordpress.org/Creating_Tables_with_Plugins
		global $wpdb;
		$table_name = self::$prefix . $table_name;
		$charset_collate = $wpdb->get_charset_collate();
		$table_sql = "CREATE TABLE $table_name ($table_definition) $charset_collate";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($table_sql);
	}

	static private function create_tables() {
		self::create_table('keys', <<<'END'
id bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
name varchar(255),
label varchar(255),
description text,
starts_offset int(10) COMMENT 'Offset from moment it was acquired at which to start granting access.',
ends_offset int(11) COMMENT 'Offset from start, -1 for no end.',
protected boolean DEFAULT false COMMENT 'If protected then it cannot be deleted.'
END
		);

		self::create_table('locks', <<<'END'
id bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
behavior text NOT NULL COMMENT 'An array containing the necessary information to execute behaviour.',
label varchar(255) NOT NULL,
protected boolean DEFAULT false COMMENT 'If protected then it cannot be deleted.'
END
		);

		self::create_table('user_to_key', <<<'END'
id bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
user_id bigint(20) UNSIGNED NOT NULL,
key_id bigint(20) UNSIGNED NOT NULL,
acquired_at int(10) COMMENT 'Time user got the key at.'
END
		);

		self::create_table('key_to_lock', <<<'END'
id bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
key_id bigint(20) UNSIGNED NOT NULL,
lock_id bigint(20) UNSIGNED NOT NULL,
protected boolean DEFAULT false COMMENT 'If protected then it cannot be deleted.'
END
		);
	}

	static private function insert_row($table_name, $row) {
		global $wpdb;
		$wpdb->insert(self::$prefix . $table_name, $row);
	}

	static private function insert_default_rows() {
		self::insert_row('locks', [
			'behavior' => serialize([
				'version' => '1',
				'type' => 'redirect',
				'redirect_to' => 'wp-login.php',
			]),
			'label' => '登録が必要',
			'protected' => true,
		]);

		self::insert_row('keys', [
			'name' => 'tourokusumi',
			'label' => '登録済み',
			'description' => '登録したユーザに与えられる鍵',
			'starts_offset' => 0,
			'ends_offset' => -1,
			'protected' => true,
		]);

		// TODO make this more robust by first finding these values, instead of assuming 1

		self::insert_row('key_to_lock', [
			'key_id' => 1,
			'lock_id' => 1,
			'protected' => true,
		]);
	}
}
