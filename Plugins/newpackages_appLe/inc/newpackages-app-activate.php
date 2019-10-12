<?php
/**
 * @package  Feed App
 */

class newpackagesAppActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}
