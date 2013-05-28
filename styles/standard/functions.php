<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.plugin
		* @category Style
		* @file functions.php
	*/

	abstract class standard {
		public static function menu() {
			global $db, $user;
			$res2 = $db->query('SELECT * FROM '. PREFIX .'menu');
			$i = 0;
			while ($row = $db->fetch_array($res2)) {
				$i++;
				if ($row['menu_link'] == 'forum') {
					$forumPages = array(
						'forum', 'viewtopic', 'viewforum', 'newtopic', 'newpost', 'search'
					);
					
					echo '
						<li><a href="./'.$row['menu_link'].'.php" '.((in_array(template::getPage(), $forumPages)) ? 'class="active"' : '').'><img border="0" src="'.$row['menu_icon'].'" style="vertical-align:middle;"> '.$row['menu_text'].'</a></li>
					';
				} else {
					if($i == 1) {
						$class = 'roundStart';
					} else {
						$class = 'active';
					}
					echo '
						<li><a href="./'.$row['menu_link'].'.php" '.((template::getPage() == $row['menu_link']) ? 'class="'.$class.'"' : '').'><img border="0" src="'.$row['menu_icon'].'" style="vertical-align:middle;"> '.$row['menu_text'].'</a></li>
					';
				}
			}
			if ($user->row['user_level'] == ADMIN) {
				echo '<li><a href="./admin"><img border="0" src="http://cdn2.iconfinder.com/data/icons/gnomeicontheme/24x24/actions/gtk-edit.png" style="vertical-align:middle;"> Administration</a></li>';
			}


		}
		public static function languageChoiser() {
			global $db, $user;
			$res2 = $db->query('SELECT * FROM '. PREFIX .'languages');
			$i = 0;
			echo '<ul style="float: right;"><li>';
			while ($row = $db->fetch_array($res2)) {
				$i++;
					echo '<a href="forum.php?lang='.$row['lang_code'].'" style="padding-right: 5px; margin-right: 5px; padding-left: 5px; margin-left: 5px; border-right: 0px;"><img border="0" src="'.$row['lang_icon'].'" style="vertical-align:middle;"></a>';
			}
			echo '</li></ul>';
		}
	}

	/**
	 *	This function must be available in EVERY style. It gets called while initializing the style.
	 */

	function initializeStyle() {
		template::registerArea(array(
			'footer',
			'header',
			'aboveContent',
			'menuPlugin',
			'underneathContent'
		));
	}
?>