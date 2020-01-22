<?php
/**
 * @package  ApplepieFeedManagerPlugin
 */

class ApplepieFeedManagerPluginActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}
