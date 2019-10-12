<?php
/**
 * @package  Feed App
 */

class newpackagesAppDeactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
