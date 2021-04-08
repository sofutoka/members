<?php
namespace sofutoka\members\admin\tour;

if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}

require_once dirname(__FILE__) . '/class-register-scripts.php';
add_action('admin_enqueue_scripts', '\sofutoka\members\admin\tour\Register_Scripts::register');
