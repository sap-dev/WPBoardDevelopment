<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.plugin
		* @category Plugin
		* @file plugin.server.php
	*/

	namespace WPBoard\lib;

	class pluginServer {
		public static function getPluginListURL($server_url) {
			$server_url = str_replace('http://', '', $server_url);

			$slash = substr($server_url, mb_strlen($server_url) - 1, 1);

			if ($slash != '/') {
				$server_url .= '/';
			}

			return sprintf('http://%s%s', $server_url, 'plugins.json');
		}
	}