<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.index
		* @category Dashboard
		* @file index.php
	*/
	
	if (!file_exists('config.php')) {
		header('Location: install.php?step=1');
		exit;
	}
	
	require 'base.php';
	include 'lib/feed.php';

	\WPBoard\lib\feed(5);

	

	template::assign(array(
		'TITLE_TAG'	=>	'Startseite | ',
		'USER_LEGEND'	=>	$user->legend($user->row['user_level']),
		'NEWS_ACTIVE'	=>	'0',
		'PAGENR'			=>	$page,
		'PAGES_NUM'		=>	$pages_num,
		'PAGES'			=>	($pages_num > 1) ? pages($pages_num, $page, 'index.php?page=') : ''
	));

	template::display('index');
?>
