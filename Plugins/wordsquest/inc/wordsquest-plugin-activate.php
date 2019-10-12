<?php
/**
 * @package  WordsQuestPlugin
 */

class WordsQuestPluginActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}
