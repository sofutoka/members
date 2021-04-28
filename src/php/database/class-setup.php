<?php
namespace Sofutoka\Members\Database;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Setup {
	static private $prefix = 'sftk_mmbrs_';

	/**
	 * @attaches-to register_activation_hook('sofutoka-members/sofutoka-members.php')
	 */
	static public function handle_activation() {
		// 実行が済んだら次回実行するのは避けたい
		if (get_option(self::$prefix . 'created_database') != 1) {
			self::create_tables();
			self::insert_default_rows();
			self::give_registered_key_to_users();
			update_option(self::$prefix . 'created_database', 1);
		}
	}

	/**
	 * @attaches-to register_uninstall_hook('members/members.php')
	 */
	static public function handle_uninstall() {
		// TODO
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
protected boolean DEFAULT false COMMENT 'If protected then it cannot be edited or deleted.'
END
		);

		self::create_table('locks', <<<'END'
id bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
behavior text NOT NULL COMMENT 'An array containing the necessary information to execute behaviour.',
name varchar(255) NOT NULL,
label varchar(255) NOT NULL,
protected boolean DEFAULT false COMMENT 'If protected then it cannot be edited or deleted.'
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
protected boolean DEFAULT false COMMENT 'If protected then it cannot be edited or deleted.'
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
			'name' => 'tourokuhituyou',
			'label' => '登録が必要',
			'protected' => true,
		]);

		self::insert_row('keys', [
			'name' => 'tourokuzumi',
			'label' => '登録済み',
			'description' => '登録したユーザーが自動に与えられるカギ',
			'starts_offset' => 0,
			'ends_offset' => -1,
			'protected' => true,
		]);

		$registered_key = \Sofutoka\Members\Database\Key::get_registered_key();
		$registered_lock = \Sofutoka\Members\Database\Lock::get_registered_lock();

		self::insert_row('key_to_lock', [
			'key_id' => $registered_key['id'],
			'lock_id' => $registered_lock['id'],
			'protected' => true,
		]);
	}

	static private function give_registered_key_to_users() {
		global $wpdb;
		$query = 'SELECT ID FROM wp_users;';
		$users = $wpdb->get_results($query, ARRAY_A);

		$registered_key = \Sofutoka\Members\Database\Key::get_registered_key();

		foreach ($users as $user) {
			\Sofutoka\Members\Database\User_To_Key::insert_record($user['ID'], $registered_key['id']);
		}
	}
}
