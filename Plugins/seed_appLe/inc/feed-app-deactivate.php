<?php
/**
 * @package  AppLe
 */

class seedfeedAppDeactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
