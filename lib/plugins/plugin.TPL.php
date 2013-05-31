<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.plugin
		* @category Plugin
		* @file plugin.TPL.php
	*/

	final class TPL extends plugin {
		public function addToArea($area, $content) {
			if (!parent::hasPermission('TPL')) {
				parent::logError('Access to TPL-functions denied.', 'TPL');
				return false;
			}

			if (!$this->areaAvailable($area)) {
				parent::logError('Area <b>' . htmlspecialchars($area) . '</b> not registered.', 'TPL');
			} else {
				template::addToArea($area, $content);
			}
		}

		public function areaAvailable($area) {
			return template::areaAvailable($area);
		}
	}
?>