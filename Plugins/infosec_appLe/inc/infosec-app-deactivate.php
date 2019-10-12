<?php
/**
 * @package  Feed App
 */

class infosecAppDeactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
