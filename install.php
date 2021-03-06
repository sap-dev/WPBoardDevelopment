<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.plugin
		* @category Installer
		* @file install.php
	*/

	include('lib/constants.php');
	
	if (!is_writable(dirname(__FILE__) . '/')) chmod(dirname(__FILE__) . '/', 0755);

	if (is_file('config.incomplete.php')) {
		include 'config.incomplete.php';

		if ($mysql_installed && !$admin_installed && $_GET['step'] != 3 && $_GET['step'] != 4) {
			header("Location: ./install.php?step=3");
		} else if ($mysql_installed && $admin_installed) {
			header("Location: ./");
		}
	} else {
		
	}

	function read_ini($key) {
		$read = ini_get($key);

		if ($read == '1' || $read == 1 || strtolower($read) == 'on') return true;
		if ($read == '0' || $read == 0 || strtolower($read) == 'off') return false;

		return $read;
	}

	function dbChars($str) {
		return mysql_real_escape_string($str);
	}

	/**
	 *	@name 	insertMySQLData
	 *			Establishes a MySQL-connection, inserts data and writes the config-file.
	 *
	 *	@param 	string $host
	 *	@param 	string $username
	 * 	@param 	string $password
	 * 	@param 	string $database
	 *	@param 	string $prefix
	 *
	 *	@return array
	 */

	function insertMySQLData($host, $username, $password, $database, $prefix) {
		$error = 0;

		$connect = @mysql_connect($host, $username, $password) or ($error = 1 and $message = mysql_error());
		@mysql_select_db($database, $connect) or ($error = 1 and $message = mysql_error());

		if ($error == 1) {
			return array(
				'code'		=>	$error,
				'message'	=>	$message
			);
		}

		if (!preg_match('#[a-z_-]+$#i', $prefix)) {
			return array(
				'code'	=>	$error
			);
		}

		$uniqID = uniqid();

		$configFile = '<?php
	$hostname = "'.$host.'";
	$username = "'.$username.'";
	$password = "'.$password.'";
	$database = "'.$database.'";
	$prefix = "'.$prefix.'";
	$uniqID = "'.$uniqID.'";
?>';

		$file = fopen('config.php', 'w');
		$bytes = fwrite($file, $configFile);
		fclose($file);

		mysql_unbuffered_query('SET NAMES UTF8');
		mysql_unbuffered_query('BEGIN');

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "sessions` (
			  `session_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			  `session_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`session_id`),
			  KEY `session_time` (`session_time`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "attachements` (
			  `att_id` int(11) NOT NULL AUTO_INCREMENT,
			  `att_post` int(11) NOT NULL,
			  `att_file` text NOT NULL,
			  `att_size` varchar(255) DEFAULT NULL,
			  `att_downloads` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`att_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
		");
		
		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "menu` (
				  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
				  `menu_link` varchar(255) NOT NULL,
				  `menu_text` varchar(255) NOT NULL,
				  `menu_icon` varchar(255) NOT NULL,
				  PRIMARY KEY (`menu_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
		");
		
		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "language` (
			  `lang_id` int(11) NOT NULL AUTO_INCREMENT,
			  `lang_lang` varchar(255) NOT NULL,
			  `lang_code` varchar(255) NOT NULL,
			  `lang_translate` text NOT NULL,
			  PRIMARY KEY (`lang_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;
		");
		
		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "languages` (
			  `lang_id` int(11) NOT NULL AUTO_INCREMENT,
			  `lang_code` varchar(255) NOT NULL,
			  `lang_icon` varchar(255) NOT NULL,
			  `lang_name` varchar(255) NOT NULL,
			  PRIMARY KEY (`lang_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
		");
		
		mysql_unbuffered_query("
		INSERT INTO `" . $prefix . "languages` (`lang_id`, `lang_code`, `lang_icon`, `lang_name`) VALUES
			(1, 'de', 'images/de.png', 'Deutsch'),
			(2, 'en', 'images/en.png', 'English');
		");
		
				
		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "banlist` (
			  `ban_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `ban_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `ban_reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `by_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`ban_id`),
			  KEY `ban_time` (`ban_time`),
			  KEY `user_id` (`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."permissions` (
				`permission_id` int(10) NOT NULL AUTO_INCREMENT,
				`group_id` int(10) NOT NULL,
				`permission_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				PRIMARY KEY (`permission_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."groups` (
			  	`group_id` int(10) NOT NULL AUTO_INCREMENT,
			  	`group_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '--',
			  	PRIMARY KEY (`group_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "bots` (
			  `bot_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `bot_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `bot_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`bot_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "bots` (`bot_id`, `bot_name`, `bot_agent`) VALUES
			(1, 'AdsBot (Google)', 'AdsBot-Google'),
			(2, 'Alexa (Bot)', 'ia_archiver'),
			(3, 'Alta Vista (Bot)', 'Scooter/'),
			(4, 'Ask Jeeves (Bot)', 'Ask Jeeves'),
			(5, 'Baidu (Spider)', 'Baiduspider+('),
			(6, 'Exabot (Bot)', 'Exabot/'),
			(7, 'FAST Enterprise (Crawler)', 'FAST Enterprise Crawler'),
			(8, 'FAST WebCrawler (Crawler)', 'FAST-WebCrawler/'),
			(9, 'Francis (Bot)', 'http://www.neomo.de/'),
			(10, 'Gigabot (Bot)', 'Gigabot/'),
			(11, 'Google Adsense (Bot)', 'Mediapartners-Google'),
			(12, 'Google Desktop', 'Google Desktop'),
			(13, 'Google Feedfetcher', 'Feedfetcher-Google'),
			(14, 'Google (Bot)', 'Googlebot'),
			(15, 'Heise IT-Markt (Crawler)', 'heise-IT-Markt-Crawler'),
			(16, 'Heritrix (Crawler)', 'heritrix/1.'),
			(17, 'IBM Research (Bot)', 'ibm.com/cs/crawler'),
			(18, 'ICCrawler - ICjobs', 'ICCrawler - ICjobs'),
			(19, 'ichiro (Crawler)', 'ichiro/'),
			(20, 'Majestic-12 (Bot)', 'MJ12bot/'),
			(21, 'Metager (Bot)', 'MetagerBot/'),
			(22, 'MSN NewsBlogs', 'msnbot-NewsBlogs/'),
			(23, 'MSN (Bot)', 'msnbot/'),
			(24, 'MSNbot Media', 'msnbot-media/'),
			(25, 'NG-Search (Bot)', 'NG-Search/'),
			(26, 'Nutch (Bot)', 'http://lucene.apache.org/nutch/'),
			(27, 'Nutch/CVS (Bot)', 'NutchCVS/'),
			(28, 'OmniExplorer (Bot)', 'OmniExplorer_Bot/'),
			(29, 'Online link (Validator)', 'online link validator'),
			(30, 'psbot (Picsearch)', 'psbot/0'),
			(31, 'Seekport (Bot)', 'Seekbot/'),
			(32, 'Sensis (Crawler)', 'Sensis Web Crawler'),
			(33, 'SEO (Crawler)', 'SEO search Crawler/'),
			(34, 'Seoma (Crawler)', 'Seoma (SEO Crawler)'),
			(35, 'SEOSearch (Crawler)', 'SEOsearch/'),
			(36, 'Snappy (Bot)', 'Snappy/1.1 ( http://www.urltrends.com/ )'),
			(37, 'Steeler (Crawler)', 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/'),
			(38, 'Synoo (Bot)', 'SynooBot/'),
			(39, 'Telekom (Bot)', 'crawleradmin.t-info@telekom.de'),
			(40, 'TurnitinBot (Bot)', 'TurnitinBot/'),
			(41, 'Voyager (Bot)', 'voyager/1.0'),
			(42, 'W3 (Sitesearch)', 'W3 SiteSearch Crawler'),
			(43, 'W3C (Linkcheck)', 'W3C-checklink/'),
			(44, 'W3C (Validator)', 'W3C_*Validator'),
			(45, 'WiseNut (Bot)', 'http://www.WISEnutbot.com'),
			(46, 'YaCy (Bot)', 'yacybot'),
			(47, 'Yahoo MMCrawler (Bot)', 'Yahoo-MMCrawler/'),
			(48, 'Yahoo Slurp (Bot)', 'Yahoo! DE Slurp'),
			(49, 'Yahoo (Bot)', 'Yahoo! Slurp'),
			(50, 'YahooSeeker (Bot)', 'YahooSeeker/'),
			(51, 'Voila (Bot)', 'VoilaBot'),
			(52, 'Twiceler (Bot)', 'Twiceler');
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "config` (
			  `config_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `config_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `is_dynamic` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`config_name`),
			  KEY `is_dynamic` (`is_dynamic`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
					INSERT INTO `" . $prefix . "config` (`config_name`, `config_value`, `is_dynamic`) VALUES
					('newest_user_id', '1', 1),
					('newest_user_level', '2', 1),
					('posts_num', '0', 1),
					('topics_num', '1', 1),
					('users_num', '1', 1),
					('title', 'Titel der Seite', 0),
					('description', 'Ein Text der das Forum beschreibt', 0),
					('theme', 'standard', 0),
					('topics_perpage', '20', 0),
					('posts_perpage', '10', 0),
					('points_topic', '1', 0),
					('points_post', '2', 0),
					('enable_captcha', '1', 0),
					('enable', '0', 0),
					('enable_avatars', '1', 0),
					('posts_perday', '30', 0),
					('enable_text', '', 0),
					('enable_unlock', '0', 0),
					('enable_bots', '1', 0),
					('unlock_delete', '7', 0),
					('avatar_min_height', '50', 0),
					('avatar_min_width', '50', 0),
					('avatar_max_width', '160', 0),
					('avatar_max_height', '180', 0),
					('enable_delete', '1', 0),
					('mail_limit', '200', 0),
					('max_post_chars', '50000', 0),
					('default_avatar', 'default.png', 0),
					('index_news', 0, 0);
		");


	
		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "language` (`lang_id`, `lang_lang`, `lang_code`, `lang_translate`) VALUES
			(1, 'de', 'threads', 'Themen'),
					(2, 'de', 'thread', 'Thema'),
					(3, 'de', 'forum', 'Forum'),
					(4, 'de', 'post', 'Beitrag'),
					(5, 'de', 'posts', 'Beiträge'),
					(6, 'de', 'unknown', 'Unbekannt'),
					(7, 'de', 'nopost', 'Kein Beitrag'),
					(8, 'de', 'whoisonline', 'Wer ist online?'),
					(9, 'de', 'admin', 'Administrator'),
					(10, 'de', 'mod', 'Moderator'),
					(11, 'de', 'bot', 'Bot'),
					(12, 'de', 'legend', 'Legende'),
					(13, 'de', 'user', 'Mitglieder'),
					(14, 'de', 'nobody', 'Niemand'),
					(15, 'de', 'stat', 'Statistik'),
					(16, 'de', 'newest_member', 'Unser neustes Mitglied:'),
					(17, 'de', 'clock', 'Uhr'),
					(18, 'de', 'hello', 'Hallo'),
					(19, 'de', 'config', 'Einstellungen'),
					(20, 'de', 'logout', 'Logout'),
					(21, 'de', 'register', 'Registrieren'),
					(22, 'de', 'login', 'Login'),
					(23, 'de', 'home', 'home :)'),
					(24, 'de', 'only_admin', 'Forum ist nur für Administratoren sichtbar'),
					(25, 'de', 'only_mod', 'Forum ist nur für Moderatoren sichtbar'),
					(26, 'de', 'plugin_does_exists', 'Das von Ihnen gewählte Plugin ist nicht vorhanden - bitte wenden Sie sich an den Administrator.'),
					(27, 'de', 'attachements_text', 'Folgende Dateien wurden angehangen:'),
					(28, 'de', 'move', 'Verschieben'),
					(29, 'de', 'important', 'wichtig makieren'),
					(30, 'de', 'close', 'Schließen / Öffnen'),
					(31, 'de', 'un', 'un'),
					(32, 'de', 'thread_close', 'Thema schließen'),
					(33, 'de', 'thread_open', 'Thema öffnen'),
					(34, 'de', 'edit', 'Bearbeiten'),
					(35, 'de', 'new_topic', 'Neues Thema'),
					(36, 'de', 'at', 'vor'),
					(37, 'de', 'from', 'von'),
					(39, 'de', 'no_topics_in_forum', 'In diesem Forum existieren noch keine Themen'),
					(40, 'de', 'visitors', 'Besucher'),
					(41, 'de', 'date', 'Datum'),
					(42, 'de', 'label', 'Makierung'),
					(43, 'de', 'order', 'sortieren'),
					(44, 'de', 'write_post', 'Neuer Beitrag'),
					(45, 'de', 'register_date', 'Registriert'),
					(46, 'de', 'user_to_post', 'Sie müssen angemeldet sein, um auf ein Thema zu antworten'),
					(47, 'de', 'answer', 'Antworten'),
					(48, 'de', 'all', 'Alle'),
					(49, 'de', 'online', 'Online'),
					(50, 'de', 'offline', 'Offline'),
					(51, 'de', 'since', 'seid'),
					(52, 'de', 'clock', 'Uhr'),
					(53, 'de', 'minutes', 'Minuten'),
					(54, 'de', 'last_online', 'Zuletzt online'),
					(55, 'de', 'member_locked', 'Der Benutzer ist gesperrt'),
					(56, 'de', 'no', 'Keine'),
					(57, 'de', 'global_information', 'Allgemeine Informationen'),
					(58, 'de', 'contact', 'Kontaktmöglichkeiten'),
					(59, 'de', 'about_me', 'Über mich'),
					(60, 'de', 'config', 'Einstellungen'),
					(61, 'de', 'account', 'Account'),
					(62, 'de', 'avatar', 'Avatar'),
					(63, 'de', 'signatur', 'Signatur'),
					(64, 'de', 'profil', 'Ihr Profil'),
					(65, 'de', 'end_user', 'Account löschen'),
					(66, 'de', 'homepage', 'Webseite'),
					(67, 'de', 'options', 'Optionen'),
					(68, 'de', 'smilies_out', 'Smilies ausschalten'),
					(69, 'de', 'bbcodes_out', 'BBCodes ausschalten'),
					(70, 'de', 'url_out', 'Urls nicht verlinken'),
					(71, 'de', 'save', 'Speichern'),
					(72, 'de', 'admin_not_delete', 'Gründer können ihr Konto aus Sicherheitsgründen nicht löschen.'),
					(73, 'de', 'time_from_register', 'Mitgliedschaft besteht seit'),
					(74, 'de', 'account_delete_sure', 'Meinen Account endgültig löschen'),
					(75, 'de', 'add_new_avatar', 'Neuen Avatar hochladen'),
					(76, 'de', 'avatar_delete', 'Avatar löschen'),
					(77, 'de', 'upload', 'Hochladen'),
					(78, 'de', 'description_allowed_avatar_files', '.jpg; .jpeg; .png; .gif'),
					(79, 'de', 'edit_email', 'Emailadresse verändern'),
					(80, 'de', 'old_email', 'Aktuelle Emailadresse'),
					(81, 'de', 'new_email', 'Neue Emailadresse'),
					(82, 'de', 'old_password', 'Aktuelles Passwort'),
					(83, 'de', 'edit_password', 'Passwort ändern'),
					(84, 'de', 'new_password', 'Neues Passwort'),
					(85, 'de', 'account_delete_description', 'Dein kompletter Account wird gelöscht.\r\n						Beiträge im Forum werden nicht gelöscht.<br />\r\n						Achtung: Diese Aktion kann nicht rückgängig gemacht werden!\r\n						Gelöscht ist gelöscht!'),
					(86, 'de', 'password_again', 'Passwort wiederholen'),
					(87, 'de', 'skype', 'Skype'),
					(88, 'de', 'icq', 'ICQ'),
					(89, 'de', 'password_not_match', 'Die Passwörter sind nicht gleich.'),
					(90, 'de', 'passwort_wrong', 'Das aktuelle Passwort ist nicht korrekt'),
					(91, 'de', 'admin_delete_error', 'Gründer können das Konto nicht im Benutzerpanel löschen.'),
					(92, 'de', 'email_wrong', 'Die Emailadresse ist nicht korrekt'),
					(93, 'de', 'email_exist', 'Die Emailadresse ist bereits vorhanden'),
					(94, 'de', 'password_6_lengh', 'Das Passwort muss mindestens 6 Zeichen lang sein'),
					(95, 'de', 'file_not_allowed', 'Das Format ist nicht erlaubt'),
					(96, 'de', 'develope_from', 'entwickelt von');
			");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."forums` (
			  `forum_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `forum_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `forum_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `forum_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  `is_category` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `forum_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `forum_toplevel` int(255) DEFAULT '0',
			  `forum_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_topics` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `forum_last_post_user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `is_news` int(1) DEFAULT '0',
			  PRIMARY KEY (`forum_id`),
			  KEY `forum_order` (`forum_order`),
			  KEY `forum_level` (`forum_level`)
			) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "forums_track` (
			  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `mark_time` int(11) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`forum_id`,`user_id`),
			  KEY `forum_id` (`forum_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "forums_track` (`forum_id`, `user_id`, `mark_time`) VALUES
			(2, 1, " . time() . ");
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "mails` (
			  `mail_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `to_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `mail_text` text COLLATE utf8_unicode_ci NOT NULL,
			  `mail_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `enable_bbcodes` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_smilies` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_urls` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_signatur` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `mail_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `mail_time` int(11) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`mail_id`),
			  KEY `user_id` (`user_id`),
			  KEY `to_user_id` (`to_user_id`),
			  KEY `user_read` (`to_user_id`,`mail_read`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "online` (
			  `online_lastvisit` int(11) unsigned NOT NULL DEFAULT '0',
			  `online_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			  `online_agent` varchar(150) NOT NULL DEFAULT '0',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `user_page` varchar(255) NOT NULL DEFAULT '0',
			  KEY `online_lastvisit` (`online_lastvisit`),
			  KEY `user_id` (`user_id`),
			  KEY `ip_userid` (`online_ip`,`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "plugin_server` (
			  `server_id` int(5) NOT NULL AUTO_INCREMENT,
			  `server_name` varchar(30) NOT NULL DEFAULT 'NoName',
			  `server_url` varchar(120) DEFAULT NULL,
			  `server_status` int(2) NOT NULL DEFAULT '0',
			  `server_plugins` int(5) NOT NULL,
			  `new_plugin` int(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`server_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."plugins` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) DEFAULT NULL,
			  `package` varchar(255) DEFAULT NULL,
			  `permissions` text,
			  `dependencies` text,
			  `minVersion` varchar(255) DEFAULT NULL,
			  `maxVersion` varchar(255) DEFAULT NULL,
			  `URL` text,
			  `datum` int(255) DEFAULT NULL,
			  `version` varchar(255) DEFAULT NULL,
			  `installed` int(1) DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "poll_options` (
			  `topic_id` mediumint(8) unsigned NOT NULL,
			  `option_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `option_votes` mediumint(8) unsigned NOT NULL,
			  `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  PRIMARY KEY (`option_id`),
			  KEY `topic_id` (`topic_id`),
			  KEY `topicid_optionid` (`topic_id`,`option_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "poll_votes` (
			  `topic_id` mediumint(8) unsigned NOT NULL,
			  `user_id` mediumint(8) unsigned NOT NULL,
			  PRIMARY KEY (`topic_id`,`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "posts` (
			  `post_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `post_text` text COLLATE utf8_unicode_ci,
			  `enable_bbcodes` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_smilies` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_urls` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_signatur` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `is_topic` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `post_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `post_edit_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `post_edit_username` varchar(15) DEFAULT '0',
			  `post_edit_user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `post_edit_time` int(11) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`post_id`),
			  KEY `topic_id` (`topic_id`),
			  KEY `forum_id` (`forum_id`),
			  KEY `user_id` (`user_id`),
			  KEY `topic_post_id` (`topic_id`,`post_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		") OR die(mysql_error());

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "posts` (`post_id`, `topic_id`, `forum_id`, `user_id`, `post_text`, `enable_bbcodes`, `enable_smilies`, `enable_urls`, `enable_signatur`, `is_topic`, `post_time`, `post_edit_user_id`, `post_edit_username`, `post_edit_user_level`, `post_edit_time`) VALUES
			(1, 1, 2, 1, 'Die Installation war erfolgreich.\r\n\r\nVielen Dank für das Nutzen des WPBoards!', 1, 1, 1, 1, 1, " . time() . ", 0, '', 0, 0);
		") OR die(mysql_error());
		
		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "plugin_server` (`server_id`, `server_name`, `server_url`, `server_status`, `server_plugins`, `new_plugin`) VALUES
			(1, 'WPBoard Updateserver', 'http://update.wpboard.de/', 0, 0, 0);
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "ranks` (
			  `rank_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `rank_image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `rank_title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
			  `rank_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `rank_special` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`rank_id`),
			  KEY `rank_posts` (`rank_posts`),
			  KEY `rank_special` (`rank_special`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "ranks` (`rank_id`, `rank_image`, `rank_title`, `rank_posts`, `rank_special`) VALUES
			(1, 'gold4.gif', 'Stammgast', 100, 0),
			(2, 'gold3.gif', 'Stammgast', 90, 0),
			(3, 'gold2.gif', 'Stammgast', 80, 0),
			(4, 'gold1.gif', 'Betriebsnudel', 70, 0),
			(5, 'silber4.gif', 'Betriebsnudel', 60, 0),
			(6, 'silber3.gif', 'Betriebsnudel', 50, 0),
			(7, 'silber2.gif', 'Betriebsnudel', 40, 0),
			(8, 'silber1.gif', 'Neuling', 30, 0),
			(9, 'bronze4.gif', 'Neuling', 20, 0),
			(10, 'bronze3.gif', 'Neuling', 10, 0),
			(11, 'bronze2.gif', 'Neuling', 5, 0),
			(12, 'bronze1.gif', 'Neuling', 2, 0),
			(13, 'bronze_hidden.gif', 'Anfänger', 0, 0),
			(14, '', 'Administrator', 0, 1),
			(15, '', 'Moderator', 0, 1);
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "smilies` (
			  `smilie_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `smilie_emotion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `smilie_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`smilie_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=24 ;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "smilies` (`smilie_id`, `smilie_emotion`, `smilie_image`) VALUES
			(1, ':D', 'biggrin.gif'),
			(2, ':-D', 'biggrin.gif'),
			(3, ':)', 'biggrin.gif'),
			(4, ':-)', 'biggrin.gif'),
			(5, ':P', 'razz.gif'),
			(6, ':-P', 'razz.gif'),
			(7, ':(', 'sad.gif'),
			(8, ':-(', 'sad.gif'),
			(9, ':oops:', 'redface.gif'),
			(10, ':shock:', 'eek.gif'),
			(11, ':o', 'eek.gif'),
			(12, ':evil:', 'evil.gif'),
			(13, ':roll:', 'rolleyes.gif'),
			(14, ';)', 'wink.gif'),
			(15, ';-)', 'wink.gif'),
			(16, '8)', 'cool.gif'),
			(17, ':lol:', 'lol.gif'),
			(18, ';(', 'cry.gif'),
			(19, ':!:', 'exclaim.gif'),
			(20, ':?:', 'question.gif'),
			(21, ':arrow:', 'arrow.gif'),
			(22, ':idea:', 'idea.gif'),
			(23, ':|', 'neutral.gif');
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."styles` (
			  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) DEFAULT NULL,
			  `author` varchar(255) DEFAULT NULL,
			  `version` varchar(255) DEFAULT NULL,
			  `directory` varchar(255) DEFAULT NULL,
			  `active` int(1) DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
		");

		mysql_unbuffered_query("
			INSERT INTO `".$prefix."styles` (`id`, `title`, `author`, `version`, `directory`, `active`)
			VALUES
				(1, 'Standard', 'WPBoard', '1.0.0', 'standard', 1);
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "topics` (
			  `topic_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `topic_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `topic_important` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `topic_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `topic_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_views` int(11) unsigned NOT NULL DEFAULT '0',
			  `poll_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `poll_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `poll_votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_username` varchar(15) COLLATE utf8_unicode_ci DEFAULT '0',
			  `topic_last_post_user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_label` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`topic_id`),
			  KEY `forum_id` (`forum_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
		");

			
		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "menu` (`menu_id`, `menu_link`, `menu_text`, `menu_icon`) VALUES
				(1, 'index', 'Startseite', 'images/website.png'),
				(2, 'forum', 'Forum', 'images/forum.png'),
				(3, 'memberlist', 'Mitglieder', 'images/user.png');
		");
		
		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "topics_track` (
			  `topic_id` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `mark_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`topic_id`,`user_id`),
			  KEY `forum_id` (`forum_id`),
			  KEY `topic_id` (`topic_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "users` (
			  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `user_lastvisit` int(11) unsigned NOT NULL DEFAULT '0',
			  `username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `user_password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `user_avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `user_rank` mediumint(8) NOT NULL DEFAULT '0',
			  `user_signatur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `user_signatur_bbcodes` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `user_signatur_smilies` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `user_signatur_urls` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `user_points` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `user_ban` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `user_ip` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
			  `user_website` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `user_icq` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `user_skype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `user_login` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `user_register` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_mails` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  `user_unlock` varchar(6) CHARACTER SET utf8 NOT NULL,
			  `user_ueber` TEXT NOT NULL,
			  `user_lang` varchar(244) NOT NULL,
			  PRIMARY KEY (`user_id`),
			  UNIQUE KEY `username` (`username`),
			  UNIQUE KEY `email` (`user_email`),
			  KEY `ip` (`user_ip`),
			  KEY `user_lastvisit` (`user_lastvisit`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=1 AUTO_INCREMENT=2 ;
		");
		
		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "label` (
			  `label_id` int(11) NOT NULL AUTO_INCREMENT,
			  `label_color` varchar(255) NOT NULL,
			  `label_text` varchar(255) NOT NULL,
			  PRIMARY KEY (`label_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;
		");
		
		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "label` (`label_id`, `label_color`, `label_text`) VALUES
				(1, '#920000', 'offen'),
				(2, '', 'bestätigt'),
				(3, 'green', 'Umgesetzt'),
				(4, 'orange', 'in Bearbeitung');
			
		");
		incompleteFile('update', true, false);

		return array(
			'code'	=>	0
		);
	}

	function incompleteFile($mode, $mysqlInstalled = false, $adminInstalled = false) {
		if ($mode == 'update') {
			@unlink('config.incomplete.php');

			$file = fopen('config.incomplete.php', 'w');
			$bytes = fwrite($file, '<?php /* Itschi.installer - do not delete */ $mysql_installed = '.($mysqlInstalled ? 'true' : 'false').'; $admin_installed = '.($adminInstalled ? 'true' : 'false').'; ?>');
			fclose($file);
		} else if ($mode == 'delete') {
			@unlink('config.incomplete.php');
		}
	}

	function createAdmin($prefix, $username, $email, $password, $password2) {
		if (empty($username) || empty($email) || empty($password) || empty($password2)) {
			return array(
				'code'	=>	1,
				'message'	=>	'Du hast nicht alle Felder ausgef&uuml;llt.'
			);
		}

		if (!preg_match('#^[a-z]{1,2}[a-z0-9-_]+$#i', $username)) {
			return array(
				'code'	=>	2,
				'message'	=>	'Es d&uuml;rfen nur Buchstaben, Zahlen, Minuszeichen und Unterstriche im Benutzernamen verwendet werden.'
			);
		}

		if (mb_strlen($username) < 3 || mb_strlen($username) > 15) {
			return array(
				'code'	=>	3,
				'message'	=>	'Der Benutzername darf nur zwischen 3 und 15 Zeichen lang sein.'
			);
		}

		if (!preg_match('^([a-zA-Z0-9\.\-_]+)\@([a-zA-Z0-9]+)\.([a-zA-Z]{1,6})^i', $email)) {
			return array(
				'code'	=>	4,
				'message'	=>	'Die E-Mail-Adresse ist ung&uuml;ltig.'
			);
		}

		if (mb_strlen($password) < 6) {
			return array(
				'code'	=>	5,
				'message'	=>	'Das Passwort ist zu kurz.'
			);
		}

		if ($password != $password2) {
			return array(
				'code'	=>	6,
				'message'	=>	'Die beiden Passw&ouml;rter stimmen nicht &uuml;berein.'
			);
		}

		
		mysql_unbuffered_query("
		INSERT INTO `" . $prefix . "users` SET user_id = '1', username = '".$username."', user_password = '".md5($password)."', user_email = '".$email."', user_register = ".time().", user_avatar = '', user_signatur = '', user_ip = '', user_lang = 'de', user_website = '', user_icq = '', user_skype = '', user_unlock = '', user_ueber = '', user_level = '2', user_rank = '16', user_lastvisit = ".time()."
		") OR die(mysql_error());
		
		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "forums` (`forum_id`, `forum_name`, `forum_description`, `forum_order`, `is_category`, `forum_level`, `forum_posts`, `forum_topics`, `forum_closed`, `forum_last_post_id`, `forum_last_post_user_id`, `forum_last_post_time`, `forum_last_post_topic_id`, `forum_last_post_username`, `forum_last_post_user_level`) VALUES
			(1, 'Erste Kategorie', '', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '', 0),
			(2, 'Erstes Forum', 'Ein Text der das Forum beschreibt', 2, 0, 0, 0, 1, 0, 1, 1, " . time() . ", 1, '" . $username . "', 2);
		");
 
		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "config` (`config_name`, `config_value`, `is_dynamic`) VALUES
				('newest_username', '" . $username . "', 1),
				('email', '" . $email . "', 0)
		");
		
			mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "topics` (`topic_id`, `forum_id`, `topic_title`, `user_id`, `username`, `user_level`, `topic_time`, `topic_important`, `topic_closed`, `topic_posts`, `topic_views`, `poll_title`, `poll_time`, `poll_votes`, `topic_last_post_user_id`, `topic_last_post_time`, `topic_last_post_post_id`, `topic_last_post_username`, `topic_last_post_user_level`, `topic_last_post_id`) VALUES
			(1, 2, 'Erstes Thema', 1, '" . $username . "', 2, " . time() . ", 0, 0, 0, 0, '', 0, 0, 1, " . time() . ", 0, '" . $username . "', 2, 1);
		");

		incompleteFile('delete');

		return array(
			'code'	=>	0
		);
	}

	if ($_GET['step'] == 3 && is_file('config.php')) {
		include 'config.php';
		mysql_connect($hostname, $username, $password) or die(mysql_error());
		mysql_select_db($database) or die(mysql_error());
	}
	if($_GET['step']=="") {
		$prozent = '25';
	} else {
		$prozent = $_GET['step']*25;
	}
	echo '
		<!DOCTYPE html>
			<html>
				<head>
					<meta charset="utf-8" />
					<title>WPBoard &rsaquo; Installation</title>
					<link rel="stylesheet" href="styles/standard/style.css" />
					<link rel="stylesheet" href="styles/installer.css" />
				</head>

				<body style="background: url(http://i.imagebanana.com/img/pustmdz8/bg.png) repeat-x scroll 0 0 / 35px auto #FFFFFF;">
					<div id="wrapper">
						<div class="content">
							<img src="./images/logo.png" alt="WPBoard" style="margin-top: -80px;" />
							
							<div style="width: 600px; border: 1px solid #000000; border-radius: 3px 3px 3px 3px; height: 22px;">
								<div style="width: '.$prozent.'%; height: 100%; background: #63B6DB;">&nbsp;<span style="color: #ffffff;">'.$prozent.'% fertiggestellt</span></div>
							</div><br />
							
	';

	define('STATUS_OK', '<img src=images/ok.png>');
	define('STATUS_ERROR', '<img src=images/deleteS.png>');

	$error = 0;

	if (!is_dir('./lib/cache/')) mkdir('./lib/cache/', 0777);
	if (!is_dir('./images/avatar/')) mkdir('./images/avatar/', 0777);
	clearstatcache();

	$requires = array(
		'cache_writable'	=>	is_writable('./lib/cache/') && is_readable('./lib/cache/'),
		'config_writeable'	=>	is_writable('./'),
		'avatars_writeable'	=>	is_writable('./images/avatar/'),
		'php_version'		=>	phpversion() >= '5.3.0.0',
		'image_functions'	=>	function_exists('imagecreatefromgif') && function_exists('imagecreatefromjpeg') && function_exists('imagecreatefrompng'),
		'short_open_tag'	=>	read_ini('short_open_tag') == 1 || phpversion() >= '5.4.0.0',
		'mysql'				=>	function_exists('mysql_connect')
	);

	$optional = array(
		'allow_url_fopen'	=>	read_ini('allow_url_fopen')
	);

	if ((int)$_GET['step'] > 0 && in_array(false, $requires)) {
		echo '
			<div class="info">
				Dein Webserver erf&uuml;llt nicht alle Bedingungen zur Installation dieser Software.<br />
				<a href="./install.php" style="color: #fff;">Neu beginnen</a>
			</div>
		';

		$error = 1;
	}

	switch ($_GET['step']) {
		case 2:
			# Step 2
			$mysqlInstalled = false;

			if ($error == 0) {
				if (isset($_POST['submit'])) {
					$host = $_POST['host'];
					$username = $_POST['username'];
					$password = $_POST['password'];
					$database = $_POST['database'];
					$prefix = $_POST['prefix'];

					$error = insertMySQLData($host, $username, $password, $database, $prefix);

					if ($error['code'] == 1) {
						echo '
							<div class="info">Verbindung zur Datenbank ist fehlgeschlagen.<br /><code>'.$error['message'].'</code></div>
						';
					} else if ($error['code'] == 2) {
						echo '
							<div class="info">Der Pr&auml;fix darf nur Buchstaben und Unterstriche enthalten.</div>
						';
					} else {
						echo '
							<section>
								<h2><small style="font-size: 14px;">Schritt 2:</small> MySQL-Zugang</h2>

								<p>
									MySQL wurde eingerichtet.<br />
									<br />
								</p>

								<a href="./install.php?step=3" class="button">Weiter &rsaquo;</a>
							</section>
						';

						$mysqlInstalled = true;
					}

					$values = array(
						'host'		=>	htmlspecialchars($host),
						'username'	=>	htmlspecialchars($username),
						'password'	=>	htmlspecialchars($password),
						'database'	=>	htmlspecialchars($database),
						'prefix'	=>	htmlspecialchars($prefix)
					);
				} else {
					$values = array(
						'host'	=>	'localhost',
						'username'	=>	'',
						'password'	=>	'',
						'database'	=>	'',
						'prefix'	=>	'wpboard_'
					);
				}

				if (!$mysqlInstalled) {
					echo '
						<section>
							<h2><small style="font-size: 14px;">Schritt 2:</small> MySQL-Zugang</h2>

							<p>
								Bitte geben Sie Ihre MySQL-Zugangsdaten ein.
							</p>

							<br />

							<form method="post" action="">
								<input type="text" name="host" placeholder="MySQL-Host" value="'.$values['host'].'" /><br />
								<input type="text" name="username" placeholder="Benutzername" value="'.$values['username'].'" />
								<input type="password" name="password" placeholder="Passwort" value="'.$values['password'].'" />
								<br /><br />

								<input type="text" name="database" placeholder="Datenbank" value="'.$values['database'].'" />
								<input type="text" name="prefix" placeholder="Pr&auml;fix" value="'.$values['prefix'].'" />

								<br /><br />
								<input type="submit" value="Weiter &rsaquo;" name="submit" />
							</form>
						</section>
					';
				}
			}

			break;

		case 3:
			# Step 3
			$adminInstalled = false;

			if ($error == 0) {
				if (isset($_POST['submit'])) {
					$username2 = dbChars($_POST['username2']);
					$password = dbChars($_POST['password']);
					$password2 = dbChars($_POST['password2']);
					$email = dbChars($_POST['email']);

					$error = createAdmin($prefix, $username2, $email, $password, $password2);

					if ($error['code'] > 0) {
						echo '
							<div class="info">
								'.$error['message'].'
							</div>
						';
					} else {
						$adminInstalled = true;

						echo '
							<section>
								<h2><small style="font-size: 14px;">Schritt 3:</small> Benutzer anlegen</h2>

								<p>
									Der Benutzer wurde angelegt.
								</p>

								<br />

								<a href="./install.php?step=4" class="button">Weiter &rsaquo;</a>
							</section>
						';
					}

					$values = array(
						'username'	=>	$username2,
						'password'	=>	$password,
						'password2'	=>	$password2,
						'email'		=>	$email
					);
				}
				else {
					$values = array(
						'username'	=>	"",
						'password'	=>	"",
						'password2'	=>	"",
						'email'		=>	""
					);
				}

				if (!$adminInstalled) {
					echo '
						<section>
							<h2><small style="font-size: 14px;">Schritt 3:</small> Benutzer anlegen</h2>

							<p>
								Den Benutzernamen und das Passwort ben&ouml;tigen Sie sp&auml;ter zum Einloggen in den
								Administrationsbereich. Er ist zugleich auch Ihr Benutzer.
							</p>

							<br />

							<form method="post" action="">
								<input type="text" name="username2" value="'.$values['username2'].'" placeholder="Benutzername" />
								<input type="password" name="password" value="'.$values['password'].'" placeholder="Passwort" />
								<input type="password" name="password2" value="'.$values['password2'].'" placeholder="Passwort wiederholen" />
								<input type="email" name="email" value="'.$values['email'].'" placeholder="E-Mail-Adresse" />

								<br /><br />
								<input type="submit" value="Weiter &rsaquo;" name="submit" />
							</form>
						</section>
					';
				}
			}	

			break;

		case 4:
			# Finished
			echo '
				<section>
					<h2>Installation abgeschlossen!</h2>

					<p>
						Das WPBoard wurde fertig installiert und kann benutzt werden.<br />
						<b>Bitte löschen Sie die install.php Datei!</b>
					</p>
				</section>
			';

			break;

		default:
			# Step 1
			echo '
				<section>
					<h2>Willkommen - Version '.VERSION.'</h2>

					<p>
						<b>Willkommen beim WP-Board.</b><br />
						Damit sichergestellt werden kann, dass unsere Software auf Ihrem System l&auml;uft, muss der Webserver ein
						paar Voraussetzungen erf&uuml;llen. Wenn eine der Voraussetzungen nicht erf&uuml;llt wird,
						sprechen Sie mit Ihrem Administrator Webservers oder, wenn Sie selber der Administrator sind,
						erf&uuml;lle die Voraussetzungen.
					</p>
				</section>

				<section>
					<h2>Voraussetzungen</h2>

					<table border="0" width="100%">
						<tr>
							<td valign="top" width="175px">
								<b>lib/cache/ beschreibbar</b><br />
								<small>chmod 0777</small>
							</td>
							
							<td valign="top">
								'.($requires['cache_writable'] ? STATUS_OK : STATUS_ERROR).'<br />

								<small>wird ben&ouml;tigt, um Daten zwischenzuspeichern</small>
							</td>
						</tr>

						<tr>
							<td valign="top">
								<b>config.php beschreibbar</b>
							</td>

							<td valign="top">
								'.($requires['config_writeable'] ? STATUS_OK : STATUS_ERROR).'<br />

								<small>wird ben&ouml;tigt, um die Datenbankverbindung zu speichern</small>
							</td>
						</tr>

						<tr>
							<td valign="top">
								<b>images/avatar/ beschreibbar</b><br />
								<small>chmod 0777</small>
							</td>

							<td valign="top">
								'.($requires['avatars_writeable'] ? STATUS_OK : STATUS_ERROR).'<br />

								<small>wird ben&ouml;tigt, um hochgeladene Avatare zu speichern</small>
							</td>
						</tr>

						<tr>
							<td valign="top">
								<b>PHP-Version</b><br />
								<small>5.3 oder h&ouml;her</small>
							</td>

							<td valign="top">
								'.($requires['php_version'] ? STATUS_OK . '<br /><small>('.phpversion().')</small>' : STATUS_ERROR).'
							</td>
						</tr>

						<tr>
							<td valign="top">
								<b>MySQL vorhanden</b>
							</td>

							<td valign="top">
								'.($requires['mysql'] ? STATUS_OK : STATUS_ERROR).'
							</td>
						</td>

						<tr>
							<td valign="top">
								<b>Alle wichtigen Bildfunktionen vorhanden</b>
							</td>

							<td valign="top">
								'.($requires['image_functions'] ? STATUS_OK : STATUS_ERROR).'<br />
								<small>gebraucht werden imagecreatefromgif, imagecreatefromjpeg und imagecreatefrompng</small>
							</td>
						</tr>

						<tr>
							<td valign="top">
								<b>short_open_tag</b>
							</td>

							<td valign="top">
								'.($requires['short_open_tag'] ? STATUS_OK : STATUS_ERROR).'
							</td>
						</tr>

						<tr>
							<td valign="top">
								<small>(optional)</small>
								<b>allow_url_fopen</b>
							</td>

							<td valign="top">
								'.($optional['allow_url_fopen'] ? STATUS_OK : STATUS_ERROR).'<br />
								<small>
									allow_url_fopen erlaubt das automatische Abfragen von Plugin-Servern. Ohne
									diese Funktion stehen Plugin-Server nicht zur Verf&uuml;gung.
								</small>
							</td>
						</tr>
					</table>
				</section>

				'.(!in_array(false, $requires) ? '<a href="?step=2" class="button">Weiter &rsaquo;</a>' : '').'
			';
	}

	echo '
						</div>
					</div>
					<div style="text-align: center;">
						<a style="color: #000000; text-decoration: none; font-size: 12px;" href="http://www.wpboard.de"><strong>Software: WebPowerBoard® '.VERSION.'</strong></a>
					</div>
				</div>
			</body>
		</html>
	';
?>