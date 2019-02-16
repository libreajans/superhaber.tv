<?php if(!defined('APP')) die('...') ?>

	<script>
		function toggle(source, type)
		{
			checkboxes = document.getElementsByClassName(type);
			for(var i=0, n=checkboxes.length;i<n;i++) { checkboxes[i].checked = source.checked; }
		}
	</script>

	<form id="form1" name="form1" method="post" action="">
		<input type="hidden" name="action" value="<?=$action_type?>"/>
		<input type="hidden" name="id" value="<?=$_id?>"/>
		<input type="hidden" name="user_id" value="<?=$_id?>"/>

		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"><?=$header_subtitle?></h3>
			</div>
			<div class="box-body">
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-android-mail"></i> E-posta Adresi</span>
					<input required class="form-control" type="email" id="user_email" name="user_email" value="<?=$user_email?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-gear-b"></i> Yetki Seviyesi</span>
					<select class="form-control" id="user_status" name="user_status">
						<?=$option_user_status?>
					</select>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-person"></i> Gerçek Adı</span>
					<input required class="form-control" type="text" id="user_name" name="user_name" value="<?=$user_name?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-person"></i> Görünecek Adı</span>
					<input required class="form-control" type="text" id="user_realname" name="user_realname" value="<?=$user_realname?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-key"></i> Parola Bilgileri</span>
					<input class="form-control" autocomplete="off" type="password" id="user_pass" name="user_pass" value=""/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Editör</span>
					<select class="form-control" id="user_editor" name="user_editor">
						<?=$option_user_editor?>
					</select>
				</div>
				<div class="input-group col-md-12 clearMe">
					<?php if($do == 'edit') { ?>
						<input type="checkbox" id="user_pass_renew" name="user_pass_renew"/> <label for="user_pass_renew">Parolayı Yenile</label>
					<? } ?>
					<?php if($do == 'add') { ?>
						<input type="checkbox" id="user_pass_renew" name="user_pass_renew"/> <label for="user_pass_renew">Bu Parolayı Kullan <em>(Seçilmezse otomatik parola oluşturur)</em></label>
					<? } ?>
				</div>
				<div class="input-group col-md-12 clearMe" style="padding-top: 0px; margin-top: 0px;">
					<hr/>
					<h3 style="margin-top: 0px;">Avatar Seçiniz</h3>
					<?=$liste_avatar?>
				</div>
				<div class="buttonArea">
					<button class="btn right btn-success"><?=$action_submit?></button>
				</div>
			</div>
		</div>

		<?php if(($do == 'add' && $_auth['user_add'] == 1) || ($do == 'edit' && $_auth['user_edit'] == 1)) : ?>

		<div class="box box-danger">
			<div class="box-body">
				<h3><i class="ion ion-grid"></i> Modül Yetkileri</h3>
				<div>
					<table class="table table-bordered table-striped">
						<tr>
							<th class="left"><b>Modül Yetkileri</b></th>
							<th class="center">Listele</th>
							<th class="center">Ekle</th>
							<th class="center">Düzenle</th>
							<th class="center">Sil</th>
						</tr>
						<tr>
							<td></td>
							<td class="center" width="60"><input title="Listeleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toogle_list')"/></td>
							<td class="center" width="60"><input title="Ekleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_add')"/></td>
							<td class="center" width="60"><input title="Düzenleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_edit')"/></td>
							<td class="center" width="60"><input title="Silme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_delete')"/></td>
						</tr>

						<?php
							unset($modul_title['welcome']);
							unset($modul_title['password']);
							foreach($modul_title as $module => $module_baslik) :
						?>
						<tr>
							<td><?=$module_baslik?></td>
							<td class="center"><input class="toogle_list" type="checkbox" <?php if($auth[$module.'_list'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="<?=$module?>_list" value="<?=$module?>_list"/></td>
							<td class="center"><input class="toggle_add" type="checkbox" <?php if($auth[$module.'_add'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="<?=$module?>_add" value="<?=$module?>_add"/></td>
							<td class="center"><input class="toggle_edit" type="checkbox" <?php if($auth[$module.'_edit'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="<?=$module?>_edit" value="<?=$module?>_edit"/></td>
							<td class="center"><input class="toggle_delete" type="checkbox" <?php if($auth[$module.'_delete'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="<?=$module?>_delete" value="<?=$module?>_delete"/></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		</div>

		<div class="box box-primary">
			<div class="box-body">
				<h3><i class="ion ion-more"></i> Çeşitli Yetkiler</h3>
				<div>
					<table class="table table-bordered table-striped">
						<tr>
							<th class="left">Çeşitli Yetkiler</th>
							<th class="center" width="60"><input title="Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_others')"/></th>
						</tr>
						<tr>
							<td>Genel Haber İstatistiklerini görebilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['stat_common'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="stat_common" value="stat_common"/></td>
						</tr>
						<tr>
							<td>Kişi Haber İstatistiklerini görebilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['stat_self'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="stat_self" value="stat_self"/></td>
						</tr>
						<tr>
							<td>Kendi Giriş/Çıkış Kayıtlarını görebilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['log_self'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="log_self" value="log_self"/></td>
						</tr>
						<tr>
							<td>Diğer Giriş/Çıkış Kayıtlarını görebilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['log_others'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="log_others" value="log_others"/></td>
						</tr>
						<tr>
							<td>Anonim Erişim Denemelerini görebilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['log_anonim'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="log_anonim" value="log_anonim"/></td>
						</tr>
						<tr>
							<td>Erişim Loglarını Boşaltabilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['user_truncate'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="user_truncate" value="user_truncate"/></td>
						</tr>
						<tr>
							<td>Pasif Haberleri Boşaltabilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['content_truncate'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_truncate" value="content_truncate"/></td>
						</tr>
						<tr>
							<td>Pasif Yorumları Boşaltabilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['comment_truncate'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="comment_truncate" value="comment_truncate"/></td>
						</tr>
						<tr>
							<td>İletişim Formu Mesajlarını Boşaltabilir</td>
							<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['contact_truncate'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="contact_truncate" value="contact_truncate"/></td>
						</tr>
						<?php if(RC_MansetOrder == 1) : ?>
							<tr>
								<td>Manşet Sıralamasını Düzenleyebilir</td>
								<td class="center"><input class="toggle_others" type="checkbox" <?php if($auth['content_manset'] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_manset" value="content_manset"/></td>
							</tr>
						<?php endif ?>
					</table>
				</div>
			</div>
		</div>

		<?php if(RC_Authority == 1) : ?>
			<div class="box box-danger">
				<div class="box-body">
					<h3><i class="ion ion-mic-c"></i> Kategori Yetkileri</h3>
					<div>
						<table class="table table-bordered table-striped">
							<tr>
								<th class="left">Kategori Yetkileri</th>
								<th colspan="3" width="160">KENDİ YAZILARI</th>
								<th colspan="2" width="110">DiĞER YAZILAR</th>
							</tr>
							<tr>
								<th></th>
								<th class="center">Ekle</th>
								<th class="center">Düzenle</th>
								<th class="center">Sil</th>
								<th class="center">Düzenle</th>
								<th class="center">Sil</th>
							</tr>
							<tr>
								<td></td>
								<td class="center" width="60"><input title="Ekleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'cat_toggle_add')"/></td>
								<td class="center" width="60"><input title="Düzenleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'cat_toggle_edit')"/></td>
								<td class="center" width="60"><input title="Silme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'cat_toggle_delete')"/></td>
								<td class="center" width="60"><input title="Diğer Yazarların Yazılarını Düzenleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'cat_toggle_edit_others')"/></td>
								<td class="center" width="60"><input title="Diğer Yazarların Yazılarını Silme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'cat_toggle_delete_others')"/></td>
							</tr>
							<?php foreach($array_cat_name as $k => $v) : ?>
								<tr>
									<td><?=$v?></td>
									<td class="center"><input class="cat_toggle_add" type="checkbox" <?php if($auth['content_cat_add_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_cat_add_<?=$k?>" value="content_cat_add_<?=$k?>"/></td>
									<td class="center"><input class="cat_toggle_edit" type="checkbox" <?php if($auth['content_cat_edit_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_cat_edit_<?=$k?>" value="content_cat_edit_<?=$k?>"/></td>
									<td class="center"><input class="cat_toggle_delete" type="checkbox" <?php if($auth['content_cat_delete_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_cat_delete_<?=$k?>" value="content_cat_delete_<?=$k?>"/></td>
									<td class="center"><input class="cat_toggle_edit_others" type="checkbox" <?php if($auth['content_cat_others_edit_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_cat_others_edit_<?=$k?>" value="content_cat_others_edit_<?=$k?>"/></td>
									<td class="center"><input class="cat_toggle_delete_others" type="checkbox" <?php if($auth['content_cat_others_delete_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_cat_others_delete_<?=$k?>" value="content_cat_others_delete_<?=$k?>"/></td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			</div>
		<?php endif ?>

		<?php if(RC_Authority == 1) : ?>
			<div class="box box-primary">
				<div class="box-body">
					<h3><i class="ion ion-document"></i> Haber Tür Yetkileri</h3>
					<div>
						<table class="table table-bordered table-striped">
							<tr>
								<th class="left">Haber Tür Yetkileri</th>
								<th class="center">Ekle</th>
								<th class="center">Düzenle</th>
								<th class="center">Sil</th>
							</tr>
							<tr>
								<td></td>
								<td width="60" class="center"><input title="Ekleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_content_type_add')"/></td>
								<td width="60" class="center"><input title="Düzenleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_content_type_edit')"/></td>
								<td width="60" class="center"><input title="Silme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_content_type_delete')"/></td>
							</tr>
							<?php foreach($array_content_type as $k => $v): ?>
							<tr>
								<td><?=$v?></td>
								<td class="center">
									<input type="hidden" name="auth[]" value="content_type_add_<?=$k?>"/>
									<input class="toggle_content_type_add" type="checkbox" <?php if($auth['content_type_add_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_type_add_<?=$k?>" value="content_type_add_<?=$k?>"/>
								</td>
								<td class="center"><input class="toggle_content_type_edit" type="checkbox" <?php if($auth['content_type_edit_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_type_edit_<?=$k?>" value="content_type_edit_<?=$k?>"/></td>
								<td class="center"><input class="toggle_content_type_delete" type="checkbox" <?php if($auth['content_type_delete_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="content_type_delete_<?=$k?>" value="content_type_delete_<?=$k?>"/></td>
							</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			</div>
		<?php endif ?>

		<?php if(RC_Authority == 1) : ?>
			<div class="box box-primary">
				<div class="box-body">
					<h3><i class="ion ion-document"></i> Yazar Yetkileri</h3>
					<div>
						<table class="table table-bordered table-striped">
							<tr>
								<th class="left">Yazar Yetkileri</th>
								<th class="center">Ekle</th>
								<th class="center">Düzenle</th>
								<th class="center">Sil</th>
							</tr>
							<tr>
								<td></td>
								<td width="60" class="center"><input title="Ekleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_article_add')"/></td>
								<td width="60" class="center"><input title="Düzenleme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_article_edit')"/></td>
								<td width="60" class="center"><input title="Silme Yetkilerinin Tümünü Seç" type="checkbox" onClick="toggle(this, 'toggle_article_delete')"/></td>
							</tr>
							<?php foreach($array_authors as $k => $v): ?>
							<tr>
								<td><?=$v?></td>
								<td class="center">
									<input type="hidden" name="auth[]" value="article_add_<?=$k?>"/>
									<input class="toggle_article_add" type="checkbox" <?php if($auth['article_add_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="article_add_<?=$k?>" value="article_add_<?=$k?>"/>
								</td>
								<td class="center"><input class="toggle_article_edit" type="checkbox" <?php if($auth['article_edit_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="article_edit_<?=$k?>" value="article_edit_<?=$k?>"/></td>
								<td class="center"><input class="toggle_article_delete" type="checkbox" <?php if($auth['article_delete_'.$k] == 1) echo 'checked="checked"'; ?> name="auth[]" id="article_delete_<?=$k?>" value="article_delete_<?=$k?>"/></td>
							</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			</div>
			<?php endif ?>
		<?php endif ?>
	</form>
