<?php template::display('header'); ?>

<div class="fLeft" style="width: 59%;">
	<h1 class="reset"><a href="./forum.php">Forum</a> &rsaquo; <?=template::getVar('FORUM_NAME'); ?></h1>
</div>

<div class="fRight" style="width: 39%; text-align: right; padding-top: 2.5px;">
	&nbsp;

	<?php if (template::getVar('FORUM_CLOSED')): ?>
		Closed
	<?php elseif  (template::getVar('IS_NEWS') && !template::getVar('IS_MOD')): ?>
		
	<?php else: ?>
		<a href="newtopic.php?id=<?=template::getVar('FORUM_ID'); ?>" class="button"><?=template::getLanguage($config['selected_language'],'new_topic');?></a>
	<?php endif; ?>
</div>

<div class="clear"></div>

<br /><br />

<div id="forums">
	<?php
		if (count(template::getVar('SUBFORUMS')) > 0) {
			echo '
				<h2 class="title" style="margin-top: 0; padding-top: 0;">Unterforen</h2>
			';

			foreach (template::getVar('SUBFORUMS') as $s) {
				echo '
					<div class="item">
						<table width="100%">
							<tr>
								<td class="center" width="6%">
									<img src="./styles/standard/images/icons/topics/'.$s['forum_icon'].'.png">
								</td>

								<td style="padding: 10px;" width="50%">
									<h3>
										<a class="forum" href="viewforum.php?id='.$s['forum_id'].'" width="50%">
											'.$s['forum_name'].'
										</a>
									</h3>

									'.$s['forum_description'].'
								</td>

								<td width="10%" class="center">
									<b style="font-size: 16px;">'.$s['TOPICS'].'</b><br />
									<small class="grey">Them'.(($s['TOPICS'] == 1) ? 'a' : 'en').'</small>
								</td>

								<td width="10%" class="center">
									<b style="font-size: 16px;">'.$s['POSTS'].'</b><br />
									<small class="grey">Beitr'.(($s['POSTS'] == 1) ? 'ag' : 'äge').'</small>
								</td>

								<td width="22%" style="padding-left:10px">
							';
							
								if ($s['LAST_POST_ID']):
									echo '
										von
									';

									if ($s['LAST_POST_USER_ID']):
										echo '
											<a class="'.$s['LAST_POST_USER_LEGEND'].'" href="user.php?id='.$s['LAST_POST_USER_ID'].'">
												'.$s['LAST_POST_USERNAME'].'
											</a>
										';
									else:
										echo '
											<span>Unbekannt</span>
										';
									endif;
								
									echo '
										<a href="viewtopic.php?id='.$s['LAST_POST_TOPIC_ID'].'&p='.$s['LAST_POST_ID'].'#'.$s['LAST_POST_ID'].'">
											<img title="neusten Beitrag anzeigen" src="./styles/standard/images/neubeitrag.gif" />
										</a>
										<br />

										<span>
											<small class="grey">vor '.$s['LAST_POST_TIME'].'</small>
										</span>
									';
								else:
									echo '
										<small class="grey">-- kein Beitrag</small>
									';
								endif;
				echo '
						</td>
							</tr>
						</table>
					</div>
				';
			}

			echo '
				<div class="clear"></div>
				<h2 class="title">Themen</h2>
			';
		}

		if (isset(template::$blocks['topics'])) {
			foreach (template::$blocks['topics'] as $topic):
	?>
		<div id="topic_text_<?=$topic['ID'];?>" class="inline_div" style="z-index: 999; padding-right: 20px;">
			<?=$topic['PREVIEW_TEXT']; ?>
			<div style="position: absolute; top: 1px; right: 1px; padding: 5px;">
				<a href="#" onClick="$('#topic_text_<?=$topic['ID'];?>').hide(); $('#dunkel').hide();"><img src="images/deleteS.png"  alt="WPBoard-Image" /></a>
			</div>
		</div>
			<div class="item">
				<table class="forum_table">
					<tr>
						<?php if ($topic['NEW']): ?>
							<td class="forum_icon">
						<?php else: ?>
							<td class="forum_icon">
						<?php endif; ?>

							<img src="styles/standard/images/icons/topics/<?=$topic['ICON']; ?>topic.png" onClick="$('#menu_<?=$topic['ID'];?>').slideToggle();"  alt="WPBoard-Image" />
							<div id="menu_<?=$topic['ID'];?>" class="menu_topics">
								<?php if ($user->row): ?>
									<?php if (template::getVar('IS_MOD')): ?>
										<a href="movetopic.php?id=<?=$topic['ID']; ?>"><img src="images/moveS.gif" alt="WPBoard-Image" /> <?=template::getLanguage($config['selected_language'],'move');?></a><br />
										<a href="newtopic.php?edit=1&id=<?=$topic['FIRST_POST']; ?>"><img src="images/editS.png" alt="WPBoard-Image" /> <?=template::getLanguage($config['selected_language'],'edit');?></a><br />
										<a href="viewtopic.php?id=<?=$topic['ID']; ?>&important=1"><img src="images/pinS.png" alt="WPBoard-Image" /> <?php if ($topic['ICON']=="info"): ?><?=template::getLanguage($config['selected_language'],'un');?><?php endif; ?><?=template::getLanguage($config['selected_language'],'important');?></a><br />
										<a href="viewtopic.php?id=<?=$topic['ID']; ?>&close=1"><img src="images/lockS.png" alt="WPBoard-Image" /> <?php if ($topic['ICON']=="closed"): ?><?=template::getLanguage($config['selected_language'],'thread_open');?><?php else: ?><?=template::getLanguage($config['selected_language'],'thread_close');?><?php endif; ?></a>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							</td>

						<td class="thread_35">
							<?php if ($topic['NEW']): ?>
								<? if($topic['LABEL_EXIST']): ?><?=$topic['LABEL'];?>&nbsp;<? endif; ?><a href="viewtopic.php?id=<?=$topic['ID']; ?>&view=track#post">
									<img alt="Neuster ungelesener Beitrag" src="./styles/standard/images/neubeitrag.gif" />
								</a>
							<?php endif; ?>

							<? if($topic['LABEL_EXIST']): ?><?=$topic['LABEL'];?>&nbsp;<? endif; ?> <a class="forum" href="viewtopic.php?id=<?=$topic['ID']; ?>"><?=$topic['TITLE']; ?></a>

							<?=template::getLanguage($config['selected_language'],'from');?>
							<?php if ($topic['USER_ID']): ?>
								<a class="<?=$topic['USER_LEGEND']; ?>" href="user.php?id=<?=$topic['USER_ID']; ?>"><?=$topic['USERNAME']; ?></a>
							<?php else: ?>
								<span><?=template::getLanguage($config['selected_language'],'unnown');?></span>
							<?php endif; ?>
							<?=$topic['PAGES']; ?>
							<?php if($topic['ATTACH']): ?>
								<img src="images/attachmentS.png" style="vertical-align: middle;" alt="WPBoard-Image" />
							<? endif; ?>
							<br />
							<span>
								<small class="grey"><?=template::getLanguage($config['selected_language'],'at');?> <?=$topic['TIME']; ?></small>
							</span>
						</td>
						<td class="thread_200">
							

						</td>
						<td><a href="#" onClick="$('#topic_text_<?=$topic['ID']; ?>').show(); $('#dunkel').slideToggle();"><img src="images/previewS.png" alt="WPBoard-Image" /></a></td>

						<td class="thread_10">
							<b style="font-size: 16px;"><?=$topic['POSTS']; ?></b><br />
							<small class="grey"><?php if ($topic['POSTS'] == 1): ?><?=template::getLanguage($config['selected_language'],'post');?><?php else: ?><?=template::getLanguage($config['selected_language'],'posts');?><?php endif; ?></small>
						</td>

						<td class="thread_10">
							<b style="font-size: 16px;"><?=$topic['VIEWS']; ?></b><br />
							<small class="grey"><?=template::getLanguage($config['selected_language'],'visitors');?></small>
						</td>

						<td class="thread-22">
							<div class="lastposter" style="text-align: center; height: 37px;">
							<div style="float: left; width: 25%; text-align: center;">
								<img alt="WPBoard-Image" src="./images/avatar/<?php if ($topic['LAST_POST_USER_AVATAR']): ?><?=$topic['LAST_POST_USER_AVATAR']; ?><?php else: ?><?=template::getVar('AVATAR'); ?><?php endif; ?>" height="36" width="36" />
							</div>
							<div style="float: right; width: 75%;">
							<?=template::getLanguage($config['selected_language'],'from');?>
							<?php if ($topic['LAST_POST_USER_ID']): ?>
								<a class="<?=$topic['LAST_POST_USER_LEGEND']; ?>" href="user.php?id=<?=$topic['LAST_POST_USER_ID']; ?>"><?=$topic['LAST_POST_USERNAME']; ?></a>
							<?php else: ?>
								<span><?=template::getLanguage($config['selected_language'],'unknown');?></span>
							<?php endif; ?>&nbsp;

							<a href="viewtopic.php?id=<?=$topic['ID']; ?>&amp;p=<?=$topic['LAST_POST_ID']; ?>#<?=$topic['LAST_POST_ID']; ?>">
								<img src="./styles/standard/images/neubeitrag.gif" title="Letzter Beitrag" alt="WPBoard-Image" />
							</a><br />

							<span><small class="grey"><?=template::getLanguage($config['selected_language'],'at');?> <?=$topic['LAST_POST_TIME']; ?></small></span>
							</div>
</div>
	
						</td>
					</tr>
				</table>
			</div>
	<?php
			endforeach;
		} else {
	?>

		<div class="info"><?=template::getLanguage($config['selected_language'],'no_topics_in_forum');?></div>

	<?php
		}
	?>
</div>

<br /><br />

<table class="thread_bar">
<tr>
<td class="thread_third">

<div style="position: relative; float: left;margin-right: 0px;">
							<div class="lastposter" style="width: 250px; text-align: center;">
								<form action="viewforum.php" method="GET">
									<input type="hidden" name="id" value="<?=template::getVar('FORUM_ID'); ?>">
									<select name="order" style="font-size: 10px;">
										<option value=""><?=template::getLanguage($config['selected_language'],'date');?></option>
										<option value="label"><?=template::getLanguage($config['selected_language'],'label');?></option>
										<option value="view"><?=template::getLanguage($config['selected_language'],'visitors');?></option>
									</select>
									<input type="submit" value="<?=template::getLanguage($config['selected_language'],'order');?>">
								</form>							
							</div>
						</div>

</td>
<td class="thread_third center">
<div class="fLeft" style="width: 59%;">

	<?php if (template::getVar('PAGES_NUM') > 1): ?>

	 <?=template::getVar('PAGES'); ?>

	<?php endif; ?>
</div>
</td>
<td class="thread_third">
<div class="fRight" style="width: 39%; text-align: right;">

	&nbsp;
	
	<?php if (template::getVar('FORUM_CLOSED')): ?>
		<?=template::getLanguage($config['selected_language'],'closed');?>
	<?php elseif  (template::getVar('IS_NEWS') && !template::getVar('IS_MOD')): ?>
		
	<?php else: ?>
		<a href="newtopic.php?id=<?=template::getVar('FORUM_ID'); ?>" class="button"><?=template::getLanguage($config['selected_language'],'new_topic');?></a>
	<?php endif; ?>
</div>
</td></tr></table>
<div class="clear"></div>
<?php template::display('footer'); ?>