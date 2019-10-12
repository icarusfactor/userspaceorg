<?php
/**
 * @package  AppLe
 */

class seedfeedAppActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}
