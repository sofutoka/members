<?php
namespace sofutoka\members;

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
		if ($value === 'true' || $value === true) {
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
			status_header(401);
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode([
				'type' => 'INVALID_NONCE',
				'message' => 'Invalid or expired nonce.',
			]);
			wp_die();
		}
	}
}
