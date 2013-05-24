<?php

	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.plugin
		* @category Core
		* @file plugin.php
	*/

	require 'base.php';
	$plugin = $_GET['plugin'];
	
	if(file_exists('plugins/'.$db->chars($plugin).'/files/view.php')) {
		include('plugins/'.$db->chars($plugin).'/files/view.php');
	} else {
		message_box(template::getLanguage($config['selected_language'],'plugin_does_exists'));
	}
?>
