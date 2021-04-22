<?php
if (!defined('WPINC')) {
	exit('Do not access this file directly.');
}
?>
<div class="sftk_mmbrs">
	<h1>メンバーズ：ロックとカギの設定</h1>

	<?php do_action('sftk_mmbrs__keys_locks_settings_keys_editor'); ?>
	<?php require dirname(__FILE__) . '/_keys-editor.php'; ?>
	<?php require dirname(__FILE__) . '/_locks-editor.php'; ?>
</div>
