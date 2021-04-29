<?php
namespace Sofutoka\Members\Admin\Profile;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Edit_Profile_Section {
	/**
	 * @attaches-to add_action('edit_user_profile')
	 * @attaches-to add_action('show_user_profile')
	 */
	static public function render_key_editor() {
		echo '<div id="sftk_mmbrs_edit_profile_keys_editor"></div>';
	}
}
