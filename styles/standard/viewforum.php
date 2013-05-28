<?php template::display('header'); ?>

<div class="fLeft" style="width: 59%;">
	<h1 class="reset"><a href="./forum.php">Forum</a> &rsaquo; <?=template::getVar('FORUM_NAME'); ?></h1>
</div>

<div class="fRight" style="width: 39%; text-align: right; padding-top: 2.5px;">
	&nbsp;

	<?php if (template::getVar('FORUM_CLOSED')): ?>
		Geschlossen
	<?php elseif  (template::getVar('IS_NEWS') && !template::getVar('IS_MOD')): ?>
		
	<?php else: ?>
		<a href="newtopic.php?id=<?=template::getVar('FORUM_ID'); ?>" class="button">Neues Thema</a>
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
						<table width="100%" border="0">
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
											<img title="neusten Beitrag anzeigen" border="0" src="./styles/standard/images/neubeitrag.gif" />
										</a>
										<br />

										<span>
											<small class="grey">'.$s['LAST_POST_TIME'].' Uhr</small>
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
				<a href="#" onClick="$('#topic_text_<?=$topic['ID'];?>').hide(); $('#dunkel').hide();"><img src="http://cdn1.iconfinder.com/data/icons/CrystalClear/16x16/actions/button_cancel.png"></a>
			</div>
		</div>
			<div class="item">
				<table class="form" width="100%">
					<tr>
						<?php if ($topic['NEW']): ?>
							<td class="status unread center" width="6%">
						<?php else: ?>
							<td class="status center" width="6%">
						<?php endif; ?>

							<img src="styles/standard/images/icons/topics/<?=$topic['ICON']; ?>topic.png" border="0" onClick="$('#menu_<?=$topic['ID'];?>').slideToggle();" />
							<div id="menu_<?=$topic['ID'];?>" class="menu_topics">
								<?php if ($user->row): ?>
									<?php if (template::getVar('IS_MOD')): ?>
										<a href="movetopic.php?id=<?=$topic['ID']; ?>"><img src="http://cdn2.iconfinder.com/data/icons/aspneticons_v1.0_Nov2006/move_16x16.gif"> <?=template::getLanguage($config['selected_language'],'move');?></a><br />
										<a href="newtopic.php?edit=1&id=<?=$topic['FIRST_POST']; ?>"><img src="http://cdn2.iconfinder.com/data/icons/diagona/icon/16/018.png"> <?=template::getLanguage($config['selected_language'],'edit');?></a><br />
										<a href="viewtopic.php?id=<?=$topic['ID']; ?>&important=1"><img src="http://cdn2.iconfinder.com/data/icons/fugue/icon/pin.png"> <?php if ($topic['ICON']=="info"): ?><?=template::getLanguage($config['selected_language'],'un');?><?php endif; ?><?=template::getLanguage($config['selected_language'],'important');?></a><br />
										<a href="viewtopic.php?id=<?=$topic['ID']; ?>&close=1"><img src="http://cdn2.iconfinder.com/data/icons/Siena/16/lock%20yellow.png"> <?php if ($topic['ICON']=="closed"): ?><?=template::getLanguage($config['selected_language'],'thread_open');?><?php else: ?><?=template::getLanguage($config['selected_language'],'thread_close');?><?php endif; ?></a>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							</td>

						<td width="35%">
							<?php if ($topic['NEW']): ?>
								<? if($topic['LABEL_EXIST']): ?><?=$topic['LABEL'];?>&nbsp;<? endif; ?><a href="viewtopic.php?id=<?=$topic['ID']; ?>&view=track#post">
									<img alt="Neuster ungelesener Beitrag" src="./styles/standard/images/neubeitrag.gif" border="0" />
								</a>
							<?php endif; ?>

							<? if($topic['LABEL_EXIST']): ?><?=$topic['LABEL'];?>&nbsp;<? endif; ?> <a class="forum" href="viewtopic.php?id=<?=$topic['ID']; ?>"><?=$topic['TITLE']; ?></a>

							von
							<?php if ($topic['USER_ID']): ?>
								<a class="<?=$topic['USER_LEGEND']; ?>" href="user.php?id=<?=$topic['USER_ID']; ?>"><?=$topic['USERNAME']; ?></a>
							<?php else: ?>
								<span>Unbekannt</span>
							<?php endif; ?>
							<?=$topic['PAGES']; ?>
							<?php if($topic['ATTACH']): ?>
								<img src="http://www.woltlab.com/forum/wcf/icon/attachmentS.png" style="vertical-align: middle;">
							<? endif; ?>
							<br />
							<span>
								<small class="grey"><?=$topic['TIME']; ?> Uhr</small>
							</span>
						</td><td width="200">
							

						</td>
						<td><a href="#" onClick="$('#topic_text_<?=$topic['ID']; ?>').show(); $('#dunkel').slideToggle();"><img src="http://cdn2.iconfinder.com/data/icons/humano2/16x16/actions/old-edit-find.png"></a></td>

						<td class="center" width="10%">
							<b style="font-size: 16px;"><?=$topic['POSTS']; ?></b><br />
							<small class="grey">Beitr<?php if ($topic['POSTS'] == 1): ?>ag<?php else: ?>äge<?php endif; ?></small>
						</td>

						<td class="center" width="10%">
							<b style="font-size: 16px;"><?=$topic['VIEWS']; ?></b><br />
							<small class="grey">Besuch<?php if ($topic['VIEWS'] == 1): ?><?php else: ?>er<?php endif; ?></small>
						</td>

						<td width="22%" style="padding-left:10px">
							<div id="lastposter" style="text-align: center; height: 37px;">
							<div style="float: left; width: 25%; text-align: center;">
								<img src="./images/avatar/<?php if ($topic['LAST_POST_USER_AVATAR']): ?><?=$topic['LAST_POST_USER_AVATAR']; ?><?php else: ?><?=template::getVar('AVATAR'); ?><?php endif; ?>" border="0" height="36" width="36" />
							</div>
							<div style="float: right; width: 75%;">
							von
							<?php if ($topic['LAST_POST_USER_ID']): ?>
								<a class="<?=$topic['LAST_POST_USER_LEGEND']; ?>" href="user.php?id=<?=$topic['LAST_POST_USER_ID']; ?>"><?=$topic['LAST_POST_USERNAME']; ?></a>
							<?php else: ?>
								<span>Unbekannt</span>
							<?php endif; ?>&nbsp;

							<a href="viewtopic.php?id=<?=$topic['ID']; ?>&p=<?=$topic['LAST_POST_ID']; ?>#<?=$topic['LAST_POST_ID']; ?>">
								<img src="./styles/standard/images/neubeitrag.gif" border="0" title="Letzter Beitrag" />
							</a><br />

							<span><small class="grey"><?=$topic['LAST_POST_TIME']; ?> Uhr</small></span>
							</div>
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

		<div class="info">In diesem Forum existieren (noch) keine Themen.</div>

	<?php
		}
	?>
</div>

<br /><br />

<table width="100%">
<tr>
<td width="33%">

<div style="position: relative; float: left;margin-right: 0px;">
							<div id="lastposter" style="width: 250px; text-align: center;">
								<form action="viewforum.php" method="GET">
									<input type="hidden" name="id" value="<?=template::getVar('FORUM_ID'); ?>">
									<select name="order" style="font-size: 10px;">
										<option value="">Datum</option>
										<option value="label">Makierung</option>
										<option value="view">Aufrufen</option>
									</select>
									<input type="submit" value="Sortieren">
								</form>							
							</div>
						</div>

</td>
<td width="33%" align="center">
<div class="fLeft" style="width: 59%;">

	<?php if (template::getVar('PAGES_NUM') > 1): ?>

	 <?=template::getVar('PAGES'); ?>

	<?php endif; ?>
</div>
</td>
<td width="33%">
<div class="fRight" style="width: 39%; text-align: right;">

	&nbsp;
	
	<?php if (template::getVar('FORUM_CLOSED')): ?>
		Geschlossen
	<?php elseif  (template::getVar('IS_NEWS') && !template::getVar('IS_MOD')): ?>
		
	<?php else: ?>
		<a href="newtopic.php?id=<?=template::getVar('FORUM_ID'); ?>" class="button">Neues Thema</a>
	<?php endif; ?>
</div>
</td></tr></table>
<div class="clear"></div>
<?php template::display('footer'); ?>