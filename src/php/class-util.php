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
		switch ($value) {
			case 'true':
			case true:
				return true;
			case 'false':
			case false:
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
}
