<?php template::display('header'); ?>
<div id="profile">
	<br />
	<div class="header">
		<div class="avatar">
			<img class="img" src="images/avatar/<?=template::getVar('AVATAR'); ?>" height="100" width="100" alt="WPBoard-Image" />
		</div>

		<div class="user">
			<h1>
				<?=template::getVar('USER_USERNAME'); ?>

				<?php if (template::getVar('IS_ONLINE')): ?>
					<small style="font-size: 12px;">&minus; <b><?=template::getLanguage($config['selected_language'],'online');?></b> <?=template::getLanguage($config['selected_language'],'since');?> <?=template::getVar('ONLINE_TIME'); ?> <?=template::getLanguage($config['selected_language'],'minutes');?></small>
				<?php else: ?>
					<small style="font-size: 12px;">&minus; <b><?=template::getLanguage($config['selected_language'],'offline');?></b> <?=template::getLanguage($config['selected_language'],'since');?> <?=template::getVar('ONLINE_TIME'); ?> <?=template::getLanguage($config['selected_language'],'clock');?></small>
				<?php endif; ?>
			</h1>
			<span class="status"><?=template::getVar('USER_USERSTATUS'); ?></span>
		</div>
		<div class="clear"></div>
	</div>
</div>

<?php if (template::getVar('BAN')): ?>
	<div class="info"><?=template::getLanguage($config['selected_language'],'member_locked');?></div>
<?php endif; ?>

<table class="forum_table">
<tr>
	<td class="forum_25">
			<div class="box_container">
				<div class="box_header"><?=template::getLanguage($config['selected_language'],'global_information');?></div>
				<div class="box_content">
					<img src="http://www.woltlab.com/forum/wcf/icon/teamM.png" style="vertical-align: middle;" alt="WPBoard-Image" /> <?=template::getVar('RANK'); ?>	<?php if (template::getVar('RANK_ICON')): ?><img src="images/ranks/<?=template::getVar('RANK_ICON'); ?>" border="0" /><?php endif; ?><br />
					<img src="http://www.woltlab.com/forum/wcf/icon/registerM.png" style="vertical-align: middle;" alt="WPBoard-Image" /> <?=template::getLanguage($config['selected_language'],'register_date');?>: <?=template::getVar('REGISTER'); ?><br />
					<img src="http://www.woltlab.com/forum/icon/postM.png" style="vertical-align: middle;" alt="WPBoard-Image" /> <?=template::getVar('POSTS'); ?> <?=template::getLanguage($config['selected_language'],'posts');?> (<?=template::getVar('PRODAY'); ?> Beiträge pro Tag)<br />
					<img src="http://www.woltlab.com/forum/wcf/icon/offlineM.png" style="vertical-align: middle;" alt="WPBoard-Image" /> <?=template::getLanguage($config['selected_language'],'last_online');?> <?=template::getVar('LAST_VISIT'); ?><br />
					</div>
			</div>
			<br />
			<div class="box_container">
				<div class="box_header"><?=template::getLanguage($config['selected_language'],'contact');?></div>
				<div class="box_content">
					<img src="http://www.woltlab.com/forum/wcf/icon/websiteM.png" style="vertical-align: middle;" alt="WPBoard-Image" /> <?php if (template::getVar('WEBSITE')): ?><a target="_blank" href="<?=template::getVar('WEBSITE'); ?>"><?=template::getVar('WEBSITE'); ?></a><?php else: ?><?=template::getLanguage($config['selected_language'],'no');?><?php endif; ?><br />
					<img src="http://www.woltlab.com/forum/wcf/icon/skypeM.png" style="vertical-align: middle;" alt="WPBoard-Image" /> <?php if (template::getVar('SKYPE')): ?><?=template::getVar('SKYPE'); ?><?php else: ?><?=template::getLanguage($config['selected_language'],'no');?><?php endif; ?><br />
					<img src="http://www.woltlab.com/forum/wcf/icon/icqM.png" style="vertical-align: middle;" alt="WPBoard-Image" /> <?php if (template::getVar('ICQ')): ?><?=template::getVar('ICQ'); ?><?php else: ?><?=template::getLanguage($config['selected_language'],'no');?><?php endif; ?><br />
				</div>
			</div>


		</td>
		<td class="forum_75">
			<div class="box_container" style="width: 100%;">
				<div class="box_header"><?=template::getLanguage($config['selected_language'],'about_me');?></div>
				<div class="box_content">
					<?=template::getVar('UEBER'); ?>	
				</div>
			</div>

			<br /><br />
		<?php if (template::getVar('SIGNATUR')): ?>
		<?=template::getVar('SIGNATUR'); ?>				
			<?php endif; ?>
	</td>
</tr>
</table>
<?php template::display('footer'); ?>