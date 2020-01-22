<?php
/**
 * @package  ApplepieFeedManagerPlugin
 */

class ApplepieFeedManagerPluginDeactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
