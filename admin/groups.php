<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.core.acp
		* @category ACP
		* @file groups.php
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	$permissions = array(
			'com.wpboard.acp.settings.canEnableDelete',
			'com.wpboard.acp.settings.canDeactivateForum',
			'com.wpboard.acp.settings.canChangeDeactivateMessage',
			'com.wpboard.acp.settings.canChangeTopicsPerPage',
			'com.wpboard.acp.settings.canChangePostsPerPage',
			'com.wpboard.acp.settings.canChangePointsPerTopic',
			'com.wpboard.acp.settings.canChangePointsPerPost',
			'com.wpboard.acp.settings.canChangeMaxPostsPerDay',
			'com.wpboard.acp.settings.canChangeMaxTextLength',
			'com.wpboard.acp.settings.canEnableCaptcha',
			'com.wpboard.acp.settings.canEnableUnlock',
			'com.wpboard.acp.settings.canChangeUnlockDelete',
			'com.wpboard.acp.settings.canEnableAvatars',
			'com.wpboard.acp.settings.canChangeDefaultAvatar',
			'com.wpboard.acp.settings.canChangeMinAvatarDimensions',
			'com.wpboard.acp.settings.canChangeMaxAvatarDimensions',
			'com.wpboard.acp.settings.canChangeMessagesLimit'
		),

		'com.wpboard.acp.page.users',
		'com.wpboard.acp.page.banlist',
		'com.wpboard.acp.page.forums',
		'com.wpboard.acp.page.bots',
		'com.wpboard.acp.page.smilies',
		'com.wpboard.acp.page.ranks',

		// GROUPS
		'com.wpboard.acp.page.groups' => array(
			'com.wpboard.acp.groups.canAddGroup',
			'com.wpboard.acp.groups.canDeleteGroup',
			'com.wpboard.acp.groups.canEditGroup'
		),

		// PLUGINS
		'com.wpboard.acp.page.plugins' => array(
			'com.wpboard.acp.plugins.canInstallPlugins',
			'com.wpboard.acp.plugins.canAddPluginServer',
			'com.wpboard.acp.plugins.canRemovePluginServer',
			'com.wpboard.acp.plugins.canEditPluginServer'
		)
	);

	template::display('groups', true);
?>