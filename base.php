<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.core
		* @category Core
		* @file base.php
	*/

	use \WPBoard\lib as lib;
	session_start();

	error_reporting(E_ALL & ~E_NOTICE);

	header('Content-Type: text/html; charset=utf-8');

	$root = dirname(__FILE__) . '/';

	require_once $root.'config.php';
	require_once $root.'lib/user.php';
	require_once $root.'lib/mysql.php';
	require_once $root.'lib/token.php';
	require_once $root.'lib/cache.php';
	require_once $root.'lib/template.php';
	require_once $root.'lib/constants.php';
	require_once $root.'lib/plugins/plugins.php';
	require_once $root.'lib/functions/functions.php';
	require_once $root.'lib/functions/functions.user.php';
	require_once $root.'lib/functions/functions.topic.php';
	require_once $root.'lib/functions/functions.date.php';
	require_once $root.'lib/functions/functions.upload.php';

	functions::init();

	require_once $root.'lib/functions/global.php';

	if (empty($prefix) || is_file('config.incomplete.php')) {
		header('Location: install.php');
		exit;
	}

	$db = new lib\mysql($hostname, $username, $password, $database);
	$user = new lib\user();
	$cache = new lib\cache();
	$token = new lib\token();
	$plugins = new lib\plugins();
	$config = config_vars();
	$page = basename($_SERVER['SCRIPT_FILENAME']);

	template::init();

	require_once $root.'lib/plugins/plugin.php';
	require_once $root.'lib/plugins/plugin.HTTP.php';
	require_once $root.'lib/plugins/plugin.SQL.php';
	require_once $root.'lib/plugins/plugin.TPL.php';
	require_once $root.'lib/plugins/plugin.utils.php';

	HTTP::init();

	unset($password);

	if ($config['enable'] && $user->row['user_level'] != ADMIN && basename($_SERVER['PHP_SELF']) != 'login.php') {
		$message = ($config['enable_text']) ? $config['enable_text'] : 'Das Forum wurde deaktiviert';
		message_box_offline($message, '', '');
	}

	$self = $_SERVER['PHP_SELF'];

	if ($token->env == USER) {
		$pRes = $db->query("SELECT package FROM " . PLUGINS_TABLE . " WHERE installed = 1");

		plugin::init_classes();

		while ($pRow = $db->fetch_object($pRes)) {
			plugin::init($pRow->package);
			plugin::run();
		}

		$db->free_result($pRes);
	}

	template::assign('admin', ($user->row['user_level'] == ADMIN));

	$token->_('user.php', 'GET', USER);
	$token->_('search.php', 'GET, POST', USER);
	$token->_('viewforum.php', 'GET', USER);
	$token->_('viewtopic.php', 'GET', USER);
	$token->_('status.php', 'POST', USER);
	$token->_('plugin.php', 'GET', USER);
	$token->_('newpost.php', 'GET', USER);
	$token->_('plugins.php', 'GET', USER);
	$token->_('admin/settings.php', 'GET', USER);
	

	$token->check('POST', $_POST);
	$token->check('GET', $_GET);

	if($_SESSION['admin_menu']=="") {
		if($_GET['menu']) {
			$_SESSION['admin_menu'] = $_GET['menu'];
		} else {
			$_SESSION['admin_menu'] = '1';
		}
	} else {
		if($_GET['menu']) {
			$_SESSION['admin_menu'] = $_GET['menu'];
		} else {
			$_SESSION['admin_menu'] = $_SESSION['admin_menu'];
		}
	}
	template::assign("menu",$_SESSION['admin_menu']);
	
	if($user->row['user_lang'] AND $_GET['lang']) {
		$config['selected_language'] = $_GET['lang'];
		$_SESSION['lang'] = $_GET['lang'];
	}	
	if($user->row['user_lang']) {
		if($_SESSION['lang']) {
			$config['selected_language'] = $_SESSION['lang'];
		} else {
			$config['selected_language'] = $user->row['user_lang'];
		}
	} else {
		if($_SESSION['lang']) {
			if($_GET['lang']) {
				$config['selected_language'] = $_GET['lang'];
				$_SESSION['lang'] = $config['selected_language'];
			} else {
				$config['selected_language'] = $_SESSION['lang'];
			}
		} else {
			if($_GET['lang']) {
				$config['selected_language'] = $_GET['lang'];
				$_SESSION['lang'] = $config['selected_language'];
			} else {
				$config['selected_language'] = $config['selected_language'];
			}
		}
	}	
	if(!template::existLanguage($config['selected_language'])) {
		$config['selected_language'] = 'de';
	}
?>
