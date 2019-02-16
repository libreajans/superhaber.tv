<?php if(!defined('APP')) die('...') ?>

	<?=$uyarilar_text?>

	<?php if($do <> 'add' && $content_tags == "") :?>
		<div class="box box-solid bg-red">
			<div class="box-body-wp">
				<i class="ion ion-alert-circled"></i>&nbsp;&nbsp;&nbsp;&nbsp;Haber etiketlerini boş bırakmayınız.
			</div>
		</div>
	<?php endif ?>

	<?php if($do <> 'add' && $content_title_seo == "") :?>
		<div class="box box-solid bg-red">
			<div class="box-body-wp">
				<i class="ion ion-alert-circled"></i>&nbsp;&nbsp;&nbsp;&nbsp;Meta Title alanını boş bırakmayınız.
			</div>
		</div>
	<?php endif ?>

	<?php if($text_error_manset_title_dublicate == 1) :?>
		<div class="box box-solid bg-red"><div class="box-body-wp"><?=$text_error_manset_title_dublicate_text?></div></div>
	<?php endif;?>

	<?php if($aspect_error_image == 1) :?>
		<div class="box box-solid bg-red"><div class="box-body-wp"><?=$aspect_error_image_text?></div></div>
	<?php endif;?>

	<?php if($content_type <> 0) : ?>
		<?php if($aspect_error_image_manset == 1) :?>
			<div class="box box-solid bg-red"><div class="box-body-wp"><?=$aspect_error_image_manset_text?></div></div>
		<?php endif;?>
	<?php endif;?>

	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jMetro/jquery-ui.css">
	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jTag/css/jquery.tagit.css"/>

	<script src="<?=G_VENDOR_TINYMCE?>tinymce.min.js"></script>
	<script src="<?=G_VENDOR_TINYMCE?>editor.minimal.js"></script>

	<script src="<?=G_VENDOR_JQUERY?>jUi/jquery-ui.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jCountable/jquery.simplyCountable.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jTag/js/tag-it.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jAreYouSure/jquery.are-you-sure.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jAreYouSure/ays-beforeunload-shim.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jquery.lazyloadxt.min.js"></script>


	<script>$(function() { $('#content_tags').tagit({allowSpaces: true});1});</script>
	<script>$(function() { $('#content_time').datetimepicker({lang:'tr', timepicker:true, format:'Y-m-d H:i:s'}); }); </script>
	<script>$(function() { $('#form1').areYouSure(); }); </script>
	<?php if(RC_ForceSeo == 1) : ?>
		<script>$(function() { $('#content_desc').simplyCountable( { counter: '#counter_content_desc', strictMax:true, maxCount: <?=$array_maxlength['h2']?>}); }); </script>
	<?php else : ?>
		<script>$(function() { $('#content_desc').simplyCountable( { counter: '#counter_content_desc', strictMax:true, maxCount: 950}); }); </script>
	<?php endif ?>

	<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="action" value="<?=$action_type?>"/>
		<input type="hidden" name="id" value="<?=$_id?>"/>
		<input type="hidden" name="content_template" value="<?=$content_template?>"/>
		<input type="hidden" name="content_image_dir" value="<?=$content_image_dir?>"/>
		<!-- Aktardığımız içeriklerin orjinal url bilgilerini burada tutuyoruz -->
		<input type="hidden" name="content_redirect" value="<?=$content_redirect?>"/>
		<!-- Galeri Sayfasında Yorum Ekleme olmaması sebebiyle yorumları daima açık gibi gösteriyoruz -->
		<input type="hidden" name="content_comment_status" value="1"/>
		<div class="box box-primary">

			<?php include ACP_MODULE_PATH.'content/form.block/block.header_submit.php'; ?>

			<div class="box-body">

				<?php include ACP_MODULE_PATH.'content/form.block/block.baslik.php'; ?>

				<div class="input-group col-md-4" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> İçerik Tipi</span>
					<select required class="form-control" id="content_type" name="content_type">
						<?=$option_type?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select required class="form-control" id="content_cat" name="content_cat">
						<?=$option_cat?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="content_cat2" name="content_cat2">
						<?=$option_cat2?>
					</select>
				</div>

				<?php include ACP_MODULE_PATH.'content/form.block/block.ozet.php'; ?>

				<?php include ACP_MODULE_PATH.'content/form.block/block.etiket.php'; ?>

				<?php include ACP_MODULE_PATH.'content/form.block/block.seo.php'; ?>

				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>

		<div class="box box-danger box-n collapsed-box">
			<div class="box-header">
				<h3 class="box-title"><i class="ion ion-document"></i> Alternatif Alanlar</h3>
				<div class="box-tools pull-right">
					<i class="btn btn-default btn-sm" data-widget="collapse"><i class="ion ion-minus"></i></i>
				</div>
			</div>
			<div style="display: none;" class="box-body">
				<div class="input-group col-md-4" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Tarih</span>
					<input required placeholder="Tarih" class="form-control datepicker" type="text" id="content_time" name="content_time" value="<?=$content_time?>"/>
				</div>
				<div class="input-group col-md-4" style="float:left; height:33px; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">
					<span class="input-group-addon">
						&nbsp;<input type="checkbox" <?=$content_ads_status_checked?> name="content_ads_status" id="content_ads_status"/> <label for="content_ads_status">REKLAM GÖSTERİLMESİN!</label>
					</span>
				</div>
				<div class="input-group col-md-4" style="float:left; height:33px; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">
					<span class="input-group-addon">
						&nbsp;<input type="checkbox" <?=$content_cat_show_status_checked?> name="content_cat_show_status" id="content_cat_show_status"/> <label for="content_cat_show_status">KATEGORI SAYFASINDA GİZLE</label>
					</span>
				</div>
				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>

		<?php include ACP_MODULE_PATH.'content/form.block/block.image.php'; ?>

		<div class="box box-primary">
			<div class="box-body">
				<div class="input-group col-md-12">
					<h4>Galeriye Fotoğraf Ekle</h4>
					<input accept="image/*" type="file" id="files" name="filesToUpload[]" multiple />
					<output id="list"></output>
				</div>
				<?php include ACP_MODULE_PATH.'content/form.block/block.footer_submit.php'; ?>
			</div>
		</div>


		<?php if($adet_resim > 0) : ?>
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title">Fotoğrafları Düzenle</h3>
				</div>
				<div class="box-body">
					<div class="input-group col-md-12" style="float:left;">
						<ul id="sortable1" class="connectedSortable">
							<?php foreach($data_list as $k => $v) :  ?>
								<li class="tindy">
									<table>
										<tr>
											<td valign="top" style="width:250px">
												<img width="250" data-src="<?=$data_list[$k]['photo_image_url']?>"/>
												<input type="hidden" name="photo_order[<?=$data_list[$k]['id']?>]" value=""/>
											</td>
											<td valign="top" style="width:700px; padding-left: 10px;">
												<textarea class="mceEditorSimple" style="width:700px; height:90px;" id="photo_text_<?=$data_list[$k]['id']?>" name="photo_text[<?=$data_list[$k]['id']?>]"><?=$data_list[$k]['photo_text']?></textarea>
											</td>
											<td valign="top" style="width:100px; padding-left:15px;">
												Sil <input class="toogle_list" title="Silme İşlemi İçin İşaretle" type="checkbox" id="imageList_<?=$data_list[$k]['id']?>" name="imageList[<?=$data_list[$k]['id']?>]"/>
											</td>
										</tr>
									</table>
								</li>
							<?php endforeach ?>
						</ul>
					</div>

					<div class="buttonArea">
						<div class="input-group col-md-6" style="float:right;">
							<button style="float:right; margin:5px 0px 0px 0px;" name="save_action" value="draft" class="btn right btn-primary">
							<?php if($content_status == '2') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_draft?></button>
							<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="publish" class="btn right btn-success">
							<?php if($content_status == '1') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_publish?></button>
						</div>
						<div class="input-group col-md-6" style="float:left;">
							<input style="display:none;" id="tumunu_sec" type="checkbox" onClick="toggle(this, 'toogle_list')"/>
							<label for="tumunu_sec" class="btn btn-danger" style="float:left;">Tümünü Silinmek Üzere İşaretle</label>
						</div>
					</div>
					<div class="input-group col-md-12 clearMe"></div>
				</div>
		<script>
			$(function() { $( "#sortable1" ).sortable(
			{
				connectWith: ".connectedSortable",
				start: function (e, ui) {
				$(ui.item).find('textarea').each(function () {
					tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));
				});
				},
				stop: function (e, ui) {
				$(ui.item).find('textarea').each(function () {
					tinymce.execCommand('mceAddEditor', true, $(this).attr('id'));
				});
				}

			}).disableSelection(); });
		</script>

		<script>
			function toggle(source, type)
			{
				checkboxes = document.getElementsByClassName(type);
				for(var i=0, n=checkboxes.length;i<n;i++) { checkboxes[i].checked = source.checked; }
			}
		</script>
	<?php endif ?>

		<script>
			function handleFileSelect(evt) {
				var files = evt.target.files; // FileList object

				// Loop through the FileList and render image files as thumbnails.
				for (var i = 0, f; f = files[i]; i++) {

				// Only process image files.
				if (!f.type.match('image.*')) {
					continue;
				}

				var reader = new FileReader();

				// Closure to capture the file information.
				reader.onload = (function(theFile) {
					return function(e) {
					// Render thumbnail.
					var span = document.createElement('span');
					span.innerHTML = ['<img class="thumb" src="', e.target.result,
										'" title="', escape(theFile.name), '"/>'].join('');
					document.getElementById('list').insertBefore(span, null);
					};
				})(f);

				// Read in the image file as a data URL.
				reader.readAsDataURL(f);
				}
			}

			document.getElementById('files').addEventListener('change', handleFileSelect, false);
		</script>
	</form>
