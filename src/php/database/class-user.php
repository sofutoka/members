<?php
namespace sofutoka\members\database;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class User {
	static public function user_has_key_for_lock($user_id, $lock_id) {
		$user_to_keys = \sofutoka\members\database\User_To_Key::get_records_for_user($user_id);
		$keys_to_lock = \sofutoka\members\database\Key_To_Lock::get_records_for_lock($lock_id);

		// 条件を満たさないkeysを外す形で進みたいと思います

		$valid_keys = array_intersect(
			array_map(function ($a) { return $a['key_id']; }, $user_to_keys),
			array_map(function ($a) { return $a['key_id']; }, $keys_to_lock)
		);

		if (count($valid_keys) > 0) {
			// これで少なくとも即アウトではないと判明しました
			// もっと細かく確認したいと思います

			$keys = \sofutoka\members\database\Key::get_user_keys($user_id);
			$keys = array_filter($keys, function ($key) use ($valid_keys) {
				return in_array((int) $key['id'], $valid_keys);
			});

			$now = time();

			// JavaScriptのArray.prototype.find()みたいな関数
			function find($arr, $fn) {
				foreach ($arr as $key => $value) {
					if ($fn($value)) {
						return $value;
					}
				}
				return null;
			}

			$valid_keys = array_filter($valid_keys, function ($valid_key_id) use ($keys, $user_to_keys, $now) {
				$key = find($keys, function ($value) use ($valid_key_id) { return $value['id'] === $valid_key_id; });
				$user_to_key = find($user_to_keys, function ($value) use ($valid_key_id) { return $value['key_id'] === $valid_key_id; });

				return (
					(int) $user_to_key['acquired_at'] + (int) $key['starts_offset'] < $now &&
					(
						(int) $key['ends_offset'] === -1 ||
						(int) $user_to_key['acquired_at'] + (int) $key['ends_offset'] > $now
					)
				);
			});

			return count($valid_keys) > 0;
		} else {
			return false;
		}
	}
}
