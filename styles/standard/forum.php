<?php template::display('header'); ?>
<div class="fLeft">
	<h1 class="title"><?=template::getLanguage($config['selected_language'], 'forum'); ?></h1>
</div>
<div class="clear"></div>
<?php template::displayArea('aboveContent'); ?>
<div id="forums">
	<?php foreach(template::$blocks['forums'] AS $forum): ?>
		<?php if ($forum['IS_CATEGORY']): ?>
			</div><br />
			<h2 class="title"><img alt="WPBoard-Image" src="images/plus.png" style="vertical-align: middle;" onClick=" $('#cat_<?=$forum['ID'];?>').slideToggle();" />
			&nbsp;<?=$forum['NAME']; ?></h2>
			<div id="cat_<?=$forum['ID'];?>"> 
			<?php else: ?>
			<div class="item" style="height: 70px;">
				<table class="forum_table">
					<tr>
						<td class="forum_icon"> 
							<img alt="WPBoard-Image" src="styles/standard/images/icons/topics/<?=$forum['ICON']; ?>.png" OnDblClick="window.location.href='viewforum.php?id=<?=$forum['ID']; ?>&amp;mark=1'" />
						</td>

						<td class="forum_name">
							<h3>
								<a class="forum" href="viewforum.php?id=<?=$forum['ID']; ?>">
									<?=$forum['NAME']; ?>
									<? if($forum['LEVEL']==3): ?>
										<img alt="WPBoard-Image" style="margin-right: 1px; vertical-align: middle;" src="images/lock.png" title="<?=template::getLanguage($config['selected_language'],'only_admin');?>" />
									<? endif; ?>
									<? if($forum['LEVEL']==2): ?>
										<img alt="WPBoard-Image" style="margin-right: 1px;  vertical-align: middle;" src="images/lock.png" title="<?=template::getLanguage($config['selected_language'],'only_mod');?>" />
									<? endif; ?>
								</a>
							</h3>

							<div style="width: 500px; word-wrap: break-word;">
								<?=$forum['DESCRIPTION']; ?>
							</div>

							<?php
								if (count($forum['SUBFORUMS']) > 0) {
									echo '<div style="font-size: 11px;padding-top: 3px;">';
									echo '<b class="grey" style=>Unterforen: </b>';

									$subforums = '';
									foreach ($forum['SUBFORUMS'] as $s) {
										$subforums .= '<a href="./viewforum.php?id='.$s['forum_id'].'">'.$s['forum_name'].'</a>, ';
									}

									echo mb_substr($subforums, 0, mb_strlen($subforums) - 2);
									echo '</div>';
								}
							?>
						</td>

						<td class="forum_stat">
							<?=$forum['TOPICS']; ?><small class="grey"> <?=(($forum['TOPICS'] == 1) ? template::getLanguage($config['selected_language'],'thread') : template::getLanguage($config['selected_language'],'threads')); ?></small><br>
							<?=$forum['POSTS']; ?><small class="grey"> <?=(($forum['POSTS'] == 1) ? template::getLanguage($config['selected_language'],'post') : template::getLanguage($config['selected_language'],'posts')) ?></small>
						</td>

					

						<td class="forum_lastpost">
							<?php if ($forum['LAST_POST_ID']): ?>
							<div class="lastposter" style="height: 37px;">
							<div style="float: left; width: 15%; text-align: center;">
								<img alt="WPBoard-Image" src="./images/avatar/<?php if ($forum['LAST_POST_USER_ID']): ?><?=$forum['USER_AVATAR']; ?><?php else: ?><?=template::getVar('AVATAR'); ?><?php endif; ?>" height="36" width="36" />
							</div>
							<div style="float: right; width: 85%;">
							<a class="forum" href="viewtopic.php?id=<?=$forum['LAST_POST_TOPIC_ID'];?>"><?=$forum['LAST_POST_TOPIC_TITLE'];?></a><? if($forum['LABEL_EXIST']): ?>&nbsp;<?=$forum['LABEL'];?><? endif; ?><br />
								<small class="grey">								
								<?php if ($forum['LAST_POST_USER_ID']): ?>
									<a class="<?=$forum['LAST_POST_USER_LEGEND']; ?>" href="user.php?id=<?=$forum['LAST_POST_USER_ID']; ?>"><?=$forum['LAST_POST_USERNAME']; ?></a>
								<?php else: ?>
									<span><?=template::getLanguage($config['selected_language'],'unknown');?></span>
								<?php endif; ?>
								</small>
								<span>
									<small class="grey">&nbsp; - &nbsp;vor <?=$forum['LAST_POST_TIME']; ?></small>
								</span>
							</div>
							</div>
							<?php else: ?>
								<div class="lastposter" style="height: 37px;"><small class="grey"><?=template::getLanguage($config['selected_language'],'nopost');?></small></div>
							<?php endif; ?>
						</td>
					</tr>
				</table>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<br />

<h2 class="title"><?=template::getLanguage($config['selected_language'],'whoisonline');?> (<?=template::getVar('PAGE_ONLINE'); ?>)</h2>
<table class="form">
	<tr>
		<td class="inhalt_icon"><img alt="WPBoard-Image" src="images/online.png"></td>
		<td>
			<?=template::getLanguage($config['selected_language'],'legend');?>: <span style="color: #26677f;"><?=template::getLanguage($config['selected_language'],'admin');?></span>, <span style="color: #3eb289;"><?=template::getLanguage($config['selected_language'],'mod');?></span>, <span style="color: #aaaaaa;"><?=template::getLanguage($config['selected_language'],'bot');?></span>
			<br /><?=template::getLanguage($config['selected_language'],'user');?>:

			<?php
			if (count(template::$blocks['online']) == 0) {
				echo '-- '.template::getLanguage($config['selected_language'],'nobody').'';
			} else {
				foreach(template::$blocks['online'] as $online): ?>
					<?=$online['SEPARATOR']; ?>

					<?php if ($online['IS_BOT']): ?>
						<span style="color:#aaa"><?=$online['BOT_NAME']; ?></span>
						<?php else: ?>
						<a class="<?=$online['LEGEND']; ?>" href="user.php?id=<?=$online['ID']; ?>"><?=$online['USERNAME']; ?></a>
						<?php endif; ?>
					<?php endforeach;
			} ?>
		</td>
	</tr>
</table>

<br />

<h2 class="title"><?=template::getLanguage($config['selected_language'],'stat');?></h2>
<table class="form">
	<tr>
		<td class="inhalt_icon"><img alt="WPBoard-Image" src="images/Statistics.png"></td>
		<td class="inhalt">
			<?=template::getVar('USERS'); ?> <?=template::getLanguage($config['selected_language'],'user');?> - <?=template::getVar('TOPICS'); ?> <?=template::getLanguage($config['selected_language'],'threads');?> - <?=template::getVar('POSTS'); ?> <?=template::getLanguage($config['selected_language'],'posts');?>
			<br /><?=template::getLanguage($config['selected_language'],'newest_member');?> <a class="<?=template::getVar('NEWEST_USER_LEGEND'); ?>" href="user.php?id=<?=template::getVar('NEWEST_USER_ID'); ?>"><?=template::getVar('NEWEST_USERNAME'); ?></a>.
		</td>
	</tr>
</table>

<?php template::display('footer'); ?>