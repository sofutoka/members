<?php
namespace sofutoka\members\admin;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

class Meta_Boxes {
	/**
	 * @attaches-to add_action('add_meta_boxes')
	 */
	public static function add_meta_boxes($type) {
		// TODO meta-boxes.inc.php for reference
		// https://wptheming.com/2010/08/custom-metabox-for-post-type/
		// https://developer.wordpress.org/block-editor/how-to-guides/backward-compatibility/meta-box/
	}

	/**
	 * @attaches-to add_action('save_post')
	 */
	public static function save_post_meta($post_id) {
		// TODO meta-box-saves.inc.php for reference
	}
}
