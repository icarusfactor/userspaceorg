<?php
/**
 * @package  WordsQuestPlugin
 */

class WordsQuestPluginDeactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
