<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.core.acp
		* @category ACP
		* @file ranks.php
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	if (isset($_GET['delete'])) {
		if (empty($_GET['ok'])) {
			message_box('Willst du den Rang wirklich löschen?', 'ranks.php?delete=' . (int)$_GET['delete'] . '&ok=1', 'Rang löschen', 'ranks.php', 'Abbrechen');
			exit;
		} else {
			$db->query('
				DELETE FROM ' . RANKS_TABLE . '
				WHERE rank_id = ' . (int)$_GET['delete']
			);

			$db->query('
				UPDATE ' . USERS_TABLE . '
				SET user_rank = 0
				WHERE user_rank = ' . (int)$_GET['delete']
			);

			$cache->delete('ranks');
		}
	}

	$res = $db->query('
		SELECT *
		FROM ' . RANKS_TABLE . '
		ORDER BY rank_special DESC, rank_posts DESC, rank_title ASC
	');

	$ranks_num = $db->num_rows($res);

	while ($row = $db->fetch_array($res)) {
		template::assignBlock('ranks', array(
			'ID'	=>	$row['rank_id'],
			'IMAGE'	=>	$row['rank_image'],
			'TITLE'	=>	htmlspecialchars($row['rank_title']),
			'POSTS'	=>	(int)$row['rank_posts']
		));
	}

	$db->free_result($res);

	template::assign(array(
		'MENU_BUTTON'	=>	8,
		'NUM'			=>	$ranks_num
	));

	template::display('ranks', true);
?>