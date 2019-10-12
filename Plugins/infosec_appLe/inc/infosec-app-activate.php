<?php
/**
 * @package  Feed App
 */

class infosecAppActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}
