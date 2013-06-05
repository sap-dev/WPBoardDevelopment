<?php template::display('header'); ?>
<table width="100%"><tr><td valign="top" width="25%">
<h2><?=template::getLanguage($config['selected_language'],'config');?></h2>
<div class="sections" style="margin-bottom: 0;">
	<ul>
		<li><a href="profile.php" <?php if (!template::getVar('MODE')): ?>class="active"<?php endif; ?>><img src="images/profileM.png" style="vertical-align: middle;"> <?=template::getLanguage($config['selected_language'],'profil');?></a></li>
		<li><a href="profile.php?mode=avatar" <?php if (template::getVar('MODE') == 'avatar'): ?>class="active"<?php endif; ?>><img src="images/avatarM.png" style="vertical-align: middle;"> <?=template::getLanguage($config['selected_language'],'avatar');?></a></li>
		<li><a href="profile.php?mode=signatur" <?php if (template::getVar('MODE') == 'signatur'): ?>class="active"<?php endif; ?>><img src="images/signaturS.png" style="vertical-align: middle;"> <?=template::getLanguage($config['selected_language'],'signatur');?></a></li>
		<li><a href="profile.php?mode=account" <?php if (template::getVar('MODE') == 'account'): ?>class="active"<?php endif; ?>><img src="images/managementM.png" style="vertical-align: middle;"> <?=template::getLanguage($config['selected_language'],'account');?></a></li>

		<?php if (template::getVar('ENABLE_DELETE')): ?>
			<li><a href="profile.php?mode=delete" <?php if (template::getVar('MODE') == 'delete'): ?>class="active"<?php endif; ?>><img src="images/deleteM.png" style="vertical-align: middle;"> <?=template::getLanguage($config['selected_language'],'end_user');?></a></li>
		<?php endif; ?>

	</ul>
</div>
</td><td valign="top" width="75%"><br />
<div class="tabs noJS" style="width: 100%;">
	<div class="content">
		<div id="profile" class="tabContent">
		
			<?php if (template::getVar('MODE') == 'signatur'): ?>
				<form action="profile.php" method="post">
				<textarea name="signatur" style="width: 590px; height: 100px;" placeholder="<?=template::getLanguage($config['selected_language'],'signatur');?>"><?=template::getVar('SIGNATUR'); ?></textarea>
				<div style="text-align: right;">
						<br /><input type="submit" name="form_profil" value="<?=template::getLanguage($config['selected_language'],'save');?>" />
					</div>
			
			<?php elseif (template::getVar('MODE') == 'avatar'): ?>

				<?php if (template::getVar('ERROR')): ?>

				<div class="info">

					<?php if (template::getVar('ERROR') == '1'): ?>		<?=template::getLanguage($config['selected_language'],'file_not_allowed');?>
					<?php endif; ?>

				</div>
				<div class="info_a"></div>

				<?php endif; ?>

				<form enctype="multipart/form-data" action="profile.php?mode=avatar" method="post">
					<div style="width: 500px; margin: auto;">
						<div class="fLeft" style="width: 180px">
							<img class="img" src="images/avatar/<?=template::getVar('AVATAR'); ?>" width="150px" height="150px" />

							<div style="position: absolute; margin-top: -18.5px; margin-left: -5px;">
								<?php if (template::getVar('AVATAR') != template::getVar('DEFAULT_AVATAR')): ?><a href="profile.php?mode=avatar&delete=1" class="button redB"><?=template::getLanguage($config['selected_language'],'avatar_delete');?></a><?php endif; ?>
							</div>
						</div>

						<div class="fRight" style="width: 320px">
							<h1><?=template::getLanguage($config['selected_language'],'add_new_avatar');?></h1>
							<input size="20" type="file" name="file" />

							<br />

							<small class="grey">
								<?=template::getLanguage($config['selected_language'],'description_allowed_avatar_files');?>
							</small>

							<br /><br />

							<input type="submit" name="submit" value="<?=template::getLanguage($config['selected_language'],'upload');?>" />
						</div>

						<div class="clear"></div>
					</div>
				</form>

			<?php elseif (template::getVar('MODE') == 'account'): ?>

				<?php if (template::getVar('ERROR') == '1'): ?>

				<div class="info">
					<?php if (template::getVar('ERROR') == '1'): ?>		<?=template::getLanguage($config['selected_language'],'password_wrong');?>
					<?php elseif (template::getVar('ERROR') == '2'): ?>	<?=template::getLanguage($config['selected_language'],'email_wrong');?>
					<?php elseif (template::getVar('ERROR') == '3'): ?>	<?=template::getLanguage($config['selected_language'],'email_exist');?>
					<?php elseif (template::getVar('ERROR') == '4'): ?>	<?=template::getLanguage($config['selected_language'],'password_wrong');?>
					<?php elseif (template::getVar('ERROR') == '5'): ?>	<?=template::getLanguage($config['selected_language'],'password_not_match');?>
					<?php elseif (template::getVar('ERROR') == '6'): ?>	<?=template::getLanguage($config['selected_language'],'password_6_lengh');?>
					<?php endif; ?>
				</div>

				<?php endif; ?>

				<div class="fLeft" style="width:100%;">
					<h1><?=template::getLanguage($config['selected_language'],'edit_email');?></h1>

					<form action="profile.php?mode=account" method="post">
						<table cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<td width="40%"><?=template::getLanguage($config['selected_language'],'old_email');?>:</td>
								<td width="60%" style="padding: 10px;" height="32px" class="grey"><?=template::getVar('USER_EMAIL'); ?></td>
							</tr>

							<tr>
								<td>N<?=template::getLanguage($config['selected_language'],'new_email');?>:</td>
								<td><input name="email" type="email" style="width:95%;" /></td>
							</tr>

							<tr>
								<td><?=template::getLanguage($config['selected_language'],'old_password');?>:</td>
								<td><input name="password" type="password" style="width:95%;" /></td>
							</tr>

							<tr>
								<td colspan="2" align="right">
									<input type="submit" name="form_email" value="<?=template::getLanguage($config['selected_language'],'edit_email');?>" />
								</td>
							</tr>
						</table><br />
						<h1><?=template::getLanguage($config['selected_language'],'edit_password');?></h1>

					<form name="eintrag" enctype="multipart/form-data" action="profile.php?mode=account" method="post">
						<table cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<td width="40%"><?=template::getLanguage($config['selected_language'],'new_password');?>:</td>
								<td width="60%"><input name="password" type="password" style="width:95%;"></td>
							</tr>

							<tr>
								<td><?=template::getLanguage($config['selected_language'],'password_again');?></td>
								<td><input name="password2" type="password" style="width:95%;"></td>
							</tr>

							<tr>
								<td><?=template::getLanguage($config['selected_language'],'old_password');?>:</td>
								<td><input name="oldpassword" type="password" style="width:95%;"></td>
							</tr>

							<tr>
								<td colspan="2" align="right">
									<input type="submit" name="form_pw" value="<?=template::getLanguage($config['selected_language'],'edit_password');?>" />
								</td>
							</tr>
						</table>
					</form>
					</form>
				</div>

				<div class="clear"></div>

			<?php elseif (template::getVar('MODE') == 'delete'): ?>

				<?php if (template::getVar('ERROR')): ?>

				<div class="info">
					<?php if (template::getVar('ERROR') == '1'): ?>		<?=template::getLanguage($config['selected_language'],'password_wrong');?>
					<?php elseif (template::getVar('ERROR') == '2'): ?>	<?=template::getLanguage($config['selected_language'],'password_not_match');?>
					<?php elseif (template::getVar('ERROR') == '3'): ?>	<?=template::getLanguage($config['selected_language'],'admin_delete_error');?>
					<?php endif; ?>
				</div>

				<?php endif; ?>

				<?php if (template::getVar('USERID') != 1) : ?>
				<form name="eintrag" enctype="multipart/form-data" action="profile.php?mode=delete" method="post">
					<div class="info">
						<?=template::getLanguage($config['selected_language'],'member_since');?> <span><?=template::getVar('REGISTER'); ?> Uhr</span>
						<br />
						<br />

						<?=template::getLanguage($config['selected_language'],'account_delete_description');?>
					</div>

					<br />

					<table border="0" cellspacing="0" cellpadding="5" width="40%" style="margin: auto;">
						<tr>
							<td align="right"><?=template::getLanguage($config['selected_language'],'old_passwort');?>:</td>
							<td><input name="password" type="password" size="30" /></td>
						</tr>

						<tr>
							<td align="right"><?=template::getLanguage($config['selected_language'],'password_again');?></td>
							<td><input name="password2" type="password" size="30" /></td>
						</tr>

						<tr>
							<td></td>
							<td>
								<input type="submit" name="form_delete" value="<?=template::getLanguage($config['selected_language'],'account_delete_sure');?>" />
							</td>
						</tr>
					</table>
				</form>
				<?php else: ?>
				<div class="info">
					<?=template::getLanguage($config['selected_language'],'time_from_register');?> <span><?=template::getVar('REGISTER'); ?> <?=template::getLanguage($config['selected_language'],'clock');?></span>
					<br />
					<br />

					<?=template::getLanguage($config['selected_language'],'admin_not_delete');?>
				</div>
				<?php endif; ?>

			<?php else: ?>

				<form action="profile.php" method="post">
					<table align="center" cellpadding="10" cellspacing="0" width="100%">
						<tr>
								<td width="10%" valign="top">
								<table class="userProfile">
									<tr>
										<td width="160px"><span><?=template::getLanguage($config['selected_language'],'homepage');?>:</span></td>
										<td width="70%">
											<input name="website" value="<?=template::getVar('WEBSITE'); ?>" style="width: 100%;" type="text" />
										</td>
									</tr>
									<tr>
										<td><span><?=template::getLanguage($config['selected_language'],'icq');?>:</span></td>
										<td>
											<input name="icq" value="<?=template::getVar('ICQ'); ?>" style="width:100%;" type="text" />
										</td>
									</tr>
									<tr>
										<td><span><?=template::getLanguage($config['selected_language'],'skype');?>:</span></td>
										<td>
											<input name="skype" value="<?=template::getVar('SKYPE'); ?>" style="width:100%;" type="text" />
										</td>
									</tr>
									<tr>
										<td><span><?=template::getLanguage($config['selected_language'],'about_me');?>:</span></td>
										<td>
											<textarea cols="70" rows="5" name="ueber"><?=template::getVar('UEBER'); ?></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>
							<td>
								
								<div class="options">
									<div class="title">
										<?=template::getLanguage($config['selected_language'],'options');?>:
									</div>

									<div class="content">
										<input id="option2" type="checkbox" value="1" name="signatur_smilies" <?php if (!template::getVar('SIGNATUR_SMILIES')): ?>checked <?php endif; ?>/>
										<label for="option2"><?=template::getLanguage($config['selected_language'],'smilies_out');?></label>

										&nbsp;&nbsp;

										<input id="option1" type="checkbox" value="1" name="signatur_bbcodes" <?php if (!template::getVar('SIGNATUR_BBCODES')): ?>checked <?php endif; ?>/>
										<label for="option1"><?=template::getLanguage($config['selected_language'],'bbcodes_out');?></label>

										&nbsp;&nbsp;

										<input id="option3" type="checkbox" value="1" name="signatur_urls" <?php if (!template::getVar('SIGNATUR_URLS')): ?>checked <?php endif; ?>/>
										<label for="option3"><?=template::getLanguage($config['selected_language'],'url_out');?></label>
									</div>

									<div class="clear"></div>
								</div>
								</td>
								</tr>
								<tr>
								<td align="right">
									<input type="submit" name="form_profil" value="<?=template::getLanguage($config['selected_language'],'save');?>" />
								</td>
							</tr>
							</td>
						</tr>
					</table>

					
				</form>

			<?php endif; ?>
		</div>
	</div>
</div>
</td></tr></table>
<?php template::display('footer'); ?>
