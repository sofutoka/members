<?php
namespace Sofutoka\Members;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

require_once dirname(__FILE__) . '/database/class-key.php';
require_once dirname(__FILE__) . '/database/class-user-to-key.php';

class Register {
	static public function grant_registered_key($user_id) {
		global $wpdb;
		$registered_key = \Sofutoka\Members\Database\Key::get_registered_key();
		\Sofutoka\Members\Database\User_To_Key::insert_record($user_id, $registered_key['id']);
	}
}
