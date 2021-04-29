<?php
namespace Sofutoka\Members;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Util {
	/**
	 * Converts into a boolean value the given value.
	 *
	 * @param $value
	 * @return bool
	 */
	static public function sanitize_bool($value): bool {
		if (is_string($value)) {
			$value = strtolower($value);
		}
		if ($value === 'true' || $value === true || $value === 'on') {
			return true;
		} elseif ($value === 'false' || $value === false) {
			return false;
		}
	}

	/**
	 * Converts into a string the given value, containing only numbers.
	 *
	 * @param $value
	 * @return string
	 */
	static public function sanitize_id($value): string {
		if (!is_string($value)) {
			$value = strval($value);
		}
		$value = preg_replace('/[^0-9]/', '', $value);
		return $value;
	}

	static public function verify_nonce($nonce, $action) {
		if (!wp_verify_nonce($nonce, $action)) {
			self::throw_ajax_error(403, 'INVALID_NONCE', 'Invalid or expired nonce.');
		}
	}

	static public function verify_capability($capability = 'add_users') {
		if (!current_user_can($capability)) {
			self::throw_ajax_error(403, 'FORBIDDEN', 'You are not allowed to call this endpoint.');
		}
	}

	static public function throw_ajax_error($status, $type, $message) {
		status_header($status);
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode([
			'type' => $type,
			'message' => $message,
		]);
		wp_die();
	}

	static public function pick($keys, $ass_arr): array {
		$new_ass_arr = [];
		foreach ($keys as $key) {
			$new_ass_arr[$key] = $ass_arr[$key];
		}
		return $new_ass_arr;
	}
}
