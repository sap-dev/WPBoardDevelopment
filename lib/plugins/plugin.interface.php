<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.plugin
		* @category Plugin
		* @file plugin.interface.php
	*/

	interface pluginInterface {
		/**
		 *	@name SQL
		**/

		public static function SQL();

		/**
		 *	@name TPL
		**/

		public static function TPL();

		/**
		 *	@name HTTP
		**/

		//public static function HTTP();

		/**
		 *	@name files
		**/

		//public static function files();

		/**
		 *	@name cache
		**/

		//public static function cache();

		/**
		 *	@name utils
		**/

		public static function utils();
	}
?>